<?php
namespace App\Controller;

use App\Enum\StatutExamen;
use App\Repository\DicomNonReconcilieRepository;
use App\Repository\ExamenRepository;
use App\Repository\ResultatDicomRepository;
use App\Service\ExamenService;
use App\Service\OrthancService;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[Route('/api/examens')]
class ExamenController extends AbstractController
{
    public function __construct(
        private ExamenService $examenService,
        private ExamenRepository $examenRepository,
        private ResultatDicomRepository $resultatDicomRepository,
        private OrthancService $orthancService,
        private LoggerInterface $logger,
        private HttpClientInterface $httpClient,
        private UrlGeneratorInterface $urlGenerator
    ) {}

    #[Route('', name: 'examen_create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $required = ['patientId', 'machineId', 'type'];
        foreach ($required as $field) {
            if (empty($data[$field])) {
                return $this->json(['error' => "Le champ '$field' est requis"], 422);
            }
        }

        try {
            $examen = $this->examenService->creerExamen($data);
            return $this->json($this->examenService->toArray($examen), 201);
        } catch (\RuntimeException $e) {
            return $this->json(['error' => $e->getMessage()], $e->getCode() ?: 400);
        }
    }

    #[Route('', name: 'examen_list', methods: ['GET'])]
    public function list(Request $request, DicomNonReconcilieRepository $dicomNonReconcilieRepository): JsonResponse
    {
        $patientId = $request->query->get('patientId');
        $statut = $request->query->get('statut');
        $view = $request->query->get('view');
        $page = max(1, (int) $request->query->get('page', 1));
        $limit = max(1, min(100, (int) $request->query->get('limit', 20)));

        try {
            if (strtoupper((string) $statut) === 'EN_ATTENTE') {
                $items = $dicomNonReconcilieRepository->findEnAttente();

                return $this->json(array_map(function ($item) {
                    $patientIdDicom = $item->getPatientIdDicom() ?? '';
                    $candidats = $patientIdDicom !== ''
                        ? $this->examenRepository->findByPatientIdentifier($patientIdDicom)
                        : [];

                    if (empty($candidats) && $item->getPatientNomDicom()) {
                        [$nom, $prenom] = array_map('trim', explode('^', $item->getPatientNomDicom(), 2));
                        if ($nom !== '') {
                            $candidats = $this->examenRepository->findByPatientNomPrenomEnCours(
                                $nom,
                                $prenom !== '' ? $prenom : null
                            );
                        }
                    }

                    return [
                        'id' => $item->getId(),
                        'orthancInstanceId' => $item->getOrthancInstanceId(),
                        'patientIdDicom' => $item->getPatientIdDicom(),
                        'patientNomDicom' => $item->getPatientNomDicom(),
                        'studyInstanceUid' => $item->getStudyInstanceUid(),
                        'modality' => $item->getModality(),
                        'statut' => $item->getStatut(),
                        'receivedAt' => $item->getReceivedAt()->format(\DateTimeInterface::ATOM),
                        'candidats' => $this->examenService->toArrayMany($candidats),
                    ];
                }, $items));
            }

            if ($view === 'resultats') {
                $examens = $this->examenRepository->findWithDicomResults($page, $limit);
            } elseif ($view === 'reconciliation-selection') {
                $examens = $this->examenRepository->findForReconciliationSelection($page, $limit);
            } elseif ($patientId) {
                $examens = $this->examenRepository->findByPatient((int) $patientId, $page, $limit);
            } elseif ($statut) {
                $normalizedStatut = strtoupper($statut) === 'TERMINE' ? 'RECU' : strtoupper($statut);
                $statutEnum = StatutExamen::tryFrom($normalizedStatut);
                if (!$statutEnum) {
                    return $this->json(['error' => 'Statut invalide'], 422);
                }
                $examens = $this->examenRepository->findByStatut($statutEnum, $page, $limit);
            } else {
                $examens = $this->examenRepository->findAllWithRelations($page, $limit);
            }

            return $this->json($this->examenService->toArrayMany($examens));
        } catch (\Exception $e) {
            $this->logger->error('Erreur lors de la récupération des examens: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);
            return $this->json(['error' => $e->getMessage()], 500);
        }
    }

    #[Route('/{id}', name: 'examen_show', methods: ['GET'])]
    public function show(int $id): JsonResponse
    {
        $examen = $this->examenRepository->find($id);
        if (!$examen) {
            return $this->json(['error' => 'Examen introuvable'], 404);
        }

        return $this->json($this->examenService->toArray($examen));
    }

    #[Route('/{id}', name: 'examen_update', methods: ['PUT'])]
    public function update(
        int $id,
        Request $request,
        DicomNonReconcilieRepository $dicomNonReconcilieRepository,
        EntityManagerInterface $em
    ): JsonResponse
    {
        $examen = $this->examenRepository->find($id);
        if (!$examen) {
            return $this->json(['error' => 'Examen introuvable'], 404);
        }

        $data = json_decode($request->getContent(), true) ?? [];

        try {
            if (!empty($data['nonReconcilieId'])) {
                $item = $dicomNonReconcilieRepository->find((int) $data['nonReconcilieId']);
                if (!$item) {
                    return $this->json(['error' => 'Resultat non reconcilie introuvable'], 404);
                }

                $this->examenService->lierResultatDicom($examen, [
                    'orthancInstanceId' => $item->getOrthancInstanceId(),
                    'studyInstanceUid' => $item->getStudyInstanceUid(),
                    'modality' => $item->getModality(),
                    'orthancUrl' => 'http://localhost:8042/instances/' . $item->getOrthancInstanceId(),
                ]);

                $this->examenService->modifierStatut($examen, StatutExamen::RECU);

                $item->setExamenReconcilie($examen);
                $item->setStatut('RECONCILIE');
                $em->flush();

                return $this->json($this->examenService->toArray($examen));
            }

            $examen = $this->examenService->modifierExamen($examen, $data);
            return $this->json($this->examenService->toArray($examen));
        } catch (\RuntimeException $e) {
            return $this->json(['error' => $e->getMessage()], $e->getCode() ?: 400);
        } catch (\Exception $e) {
            return $this->json(['error' => 'Erreur lors de la modification'], 500);
        }
    }

    #[Route('/{id}/dicom-source', name: 'examen_dicom_source', methods: ['GET'])]
    public function dicomSource(int $id): JsonResponse
    {
        $examen = $this->examenRepository->find($id);
        if (!$examen) {
            return $this->json(['error' => 'Examen introuvable'], 404);
        }

        $resultats = $this->resultatDicomRepository->findByExamen($id);
        if (!$resultats) {
            return $this->json(['error' => 'Aucun resultat DICOM trouve pour cet examen'], 404);
        }

        $resultat = $resultats[0];
        $orthancUrl = $resultat->getOrthancUrl() ?? ('http://localhost:8042/instances/' . $resultat->getOrthancInstanceId());
        $orthancFileUrl = str_ends_with($orthancUrl, '/file') ? $orthancUrl : $orthancUrl . '/file';
        $proxyUrl = $this->urlGenerator->generate(
            'examen_dicom_file',
            ['id' => $id],
            UrlGeneratorInterface::ABSOLUTE_URL
        );

        return $this->json([
            'examenId' => $id,
            'orthancInstanceId' => $resultat->getOrthancInstanceId(),
            'orthancFileUrl' => $proxyUrl,
            'source' => 'orthanc'
        ]);
    }

    #[Route('/{id}/dicom-file', name: 'examen_dicom_file', methods: ['GET'])]
    public function dicomFile(int $id): Response
    {
        $examen = $this->examenRepository->find($id);
        if (!$examen) {
            return new Response('Examen introuvable', 404);
        }

        $resultats = $this->resultatDicomRepository->findByExamen($id);
        if (!$resultats) {
            return new Response('Aucun resultat DICOM trouve pour cet examen', 404);
        }

        $resultat = $resultats[0];
        $orthancUrl = $resultat->getOrthancUrl() ?? ('http://localhost:8042/instances/' . $resultat->getOrthancInstanceId());
        $orthancFileUrl = str_ends_with($orthancUrl, '/file') ? $orthancUrl : $orthancUrl . '/file';

        try {
            $response = $this->httpClient->request('GET', $orthancFileUrl);

            if (200 !== $response->getStatusCode()) {
                return new Response('Erreur Orthanc lors du chargement du fichier DICOM', 502);
            }

            return new Response(
                $response->getContent(),
                200,
                ['Content-Type' => 'application/dicom']
            );
        } catch (\Throwable $e) {
            $this->logger->error('Erreur lors du proxy DICOM: ' . $e->getMessage(), [
                'examenId' => $id,
                'orthancFileUrl' => $orthancFileUrl,
            ]);

            return new Response('Erreur lors du chargement du fichier DICOM', 500);
        }
    }

    #[Route('/{id}/statut', name: 'examen_update_statut', methods: ['PATCH'])]
    public function updateStatut(int $id, Request $request): JsonResponse
    {
        $examen = $this->examenRepository->find($id);
        if (!$examen) {
            return $this->json(['error' => 'Examen introuvable'], 404);
        }

        $data = json_decode($request->getContent(), true);
        if (empty($data['statut'])) {
            return $this->json(['error' => "Le champ 'statut' est requis"], 422);
        }

        try {
            $normalizedStatut = strtoupper($data['statut']) === 'TERMINE' ? 'RECU' : strtoupper($data['statut']);
            $statutEnum = StatutExamen::tryFrom($normalizedStatut);
            if (!$statutEnum) {
                return $this->json(['error' => 'Statut invalide'], 422);
            }

            $examen = $this->examenService->modifierStatut($examen, $statutEnum);
            return $this->json($this->examenService->toArray($examen));
        } catch (\RuntimeException $e) {
            return $this->json(['error' => $e->getMessage()], $e->getCode() ?: 400);
        } catch (\Exception $e) {
            return $this->json(['error' => 'Erreur lors de la modification'], 500);
        }
    }

    #[Route('/{id}', name: 'examen_delete', methods: ['DELETE'])]
    public function delete(int $id): JsonResponse
    {
        $examen = $this->examenRepository->find($id);
        if (!$examen) {
            return $this->json(['error' => 'Examen introuvable'], 404);
        }

        try {
            $examen = $this->examenService->annulerExamen($examen);
            return $this->json(['message' => 'Examen annule', 'examen' => $this->examenService->toArray($examen)]);
        } catch (\RuntimeException $e) {
            return $this->json(['error' => $e->getMessage()], $e->getCode() ?: 400);
        } catch (\Exception $e) {
            return $this->json(['error' => 'Erreur lors de l annulation'], 500);
        }
    }

    #[Route('/{id}/recreer', name: 'examen_recreate', methods: ['POST'])]
    public function recreate(int $id): JsonResponse
    {
        $examen = $this->examenRepository->find($id);
        if (!$examen) {
            return $this->json(['error' => 'Examen introuvable'], 404);
        }

        if ($examen->getStatut() !== StatutExamen::ANNULE) {
            return $this->json(['error' => 'Seuls les examens annules peuvent etre recrees'], 422);
        }

        try {
            $nouvelExamen = $this->examenService->recreerExamen($examen);
            return $this->json($this->examenService->toArray($nouvelExamen), 201);
        } catch (\RuntimeException $e) {
            return $this->json(['error' => $e->getMessage()], $e->getCode() ?: 400);
        } catch (\Exception $e) {
            return $this->json(['error' => 'Erreur lors de la recreation'], 500);
        }
    }

    #[Route('/stats/resume', name: 'examen_stats', methods: ['GET'])]
    public function stats(): JsonResponse
    {
        $counts = $this->examenRepository->countGroupedByStatut();
        $planifies = $counts[StatutExamen::PLANIFIE->value] ?? 0;
        $enCours = $counts[StatutExamen::EN_COURS->value] ?? 0;
        $recus = $counts[StatutExamen::RECU->value] ?? 0;
        $annules = $counts[StatutExamen::ANNULE->value] ?? 0;

        return $this->json([
            'planifies' => $planifies,
            'en_cours' => $enCours,
            'recus' => $recus,
            'annules' => $annules,
            'enCours' => $enCours,
            'termines' => $recus,
        ]);
    }

    #[Route('/{id}/worklist', name: 'examen_worklist', methods: ['POST'])]
    public function genererWorklist(int $id): JsonResponse
    {
        $examen = $this->examenRepository->find($id);
        if (!$examen) {
            return $this->json(['error' => 'Examen introuvable'], 404);
        }

        if ($examen->getStatut() !== StatutExamen::PLANIFIE) {
            return $this->json(['error' => 'Seuls les examens planifies peuvent generer une worklist'], 422);
        }

        try {
            $success = $this->orthancService->genererWorklist($examen);

            if ($success) {
                $examen = $this->examenService->modifierStatut($examen, StatutExamen::EN_COURS);

                return $this->json([
                    'message' => 'Worklist generee avec succes',
                    'examen' => $this->examenService->toArray($examen)
                ]);
            }

            return $this->json([
                'error' => 'Impossible de generer la worklist Orthanc',
                'details' => 'Verifiez que le plugin Orthanc Worklists est installe et que l endpoint configure est accessible.'
            ], 502);
        } catch (\Exception $e) {
            $this->logger->error('Erreur lors de la generation de worklist', [
                'examenId' => $id,
                'error' => $e->getMessage()
            ]);
            return $this->json(['error' => 'Erreur interne du serveur'], 500);
        }
    }
}
