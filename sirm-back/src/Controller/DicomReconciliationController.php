<?php

namespace App\Controller;

use App\Entity\DicomNonReconcilie;
use App\Entity\Examen;
use App\Enum\StatutExamen;
use App\Repository\DicomNonReconcilieRepository;
use App\Repository\ExamenRepository;
use App\Service\ExamenService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/dicom')]
class DicomReconciliationController extends AbstractController
{
    public function __construct(
        private readonly DicomNonReconcilieRepository $dicomNonReconcilieRepository,
        private readonly ExamenRepository $examenRepository,
        private readonly ExamenService $examenService,
        private readonly EntityManagerInterface $em,
    ) {}

    #[Route('/non-reconcilies', name: 'api_dicom_non_reconcilies', methods: ['GET'])]
    public function list(): JsonResponse
    {
        return $this->json($this->buildPendingItems());
    }

    #[Route('/non-reconcilies/{id}/valider', name: 'api_dicom_validate_non_reconcilie', methods: ['POST'])]
    public function validate(int $id, Request $request): JsonResponse
    {
        return $this->doValidate($id, $request);
    }

    #[Route('/dicom/reconciliation', name: 'app_dicom_reconciliation', methods: ['GET'])]
    public function index(): JsonResponse
    {
        return $this->json($this->buildPendingItems());
    }

    #[Route('/dicom/reconcilier/{id}', name: 'app_dicom_do_reconcile', methods: ['POST'])]
    public function reconcilier(int $id, Request $request): JsonResponse
    {
        return $this->doValidate($id, $request);
    }

    private function doValidate(int $id, Request $request): JsonResponse
    {
        $item = $this->dicomNonReconcilieRepository->find($id);
        if (!$item) {
            return $this->json(['error' => 'Resultat non reconcilie introuvable'], 404);
        }

        $data = json_decode($request->getContent(), true) ?? [];
        $examenId = $data['examenId'] ?? $request->request->get('examenId') ?? $request->request->get('examen_id');
        if (!$examenId) {
            return $this->json(['error' => 'Le champ examenId est requis'], 422);
        }

        $examen = $this->examenRepository->find((int) $examenId);
        if (!$examen) {
            return $this->json(['error' => 'Examen introuvable'], 404);
        }

        if ($examen->getStatut() === StatutExamen::ANNULE) {
            return $this->json(['error' => 'Impossible de rattacher un resultat a un examen annule'], 422);
        }

        if ($examen->getStatut() === StatutExamen::RECU || $examen->getResultatDicom() !== null) {
            return $this->json(['error' => 'Cet examen a deja un resultat rattache'], 422);
        }

        try {
            $this->examenService->lierResultatDicom($examen, [
                'orthancInstanceId' => $item->getOrthancInstanceId(),
                'studyInstanceUid' => $item->getStudyInstanceUid(),
                'modality' => $item->getModality(),
                'orthancUrl' => 'http://localhost:8042/instances/' . $item->getOrthancInstanceId(),
            ]);

            $item->setExamenReconcilie($examen);
            $item->setStatut('RECONCILIE');
            $this->em->flush();

            return $this->json([
                'message' => 'Resultat valide et rattache a l examen',
                'examen' => $this->examenService->toArray($examen),
            ]);
        } catch (\RuntimeException $e) {
            return $this->json(['error' => $e->getMessage()], $e->getCode() ?: 400);
        } catch (\Exception $e) {
            return $this->json(['error' => 'Erreur lors de la validation du resultat'], 500);
        }
    }

    private function buildPendingItems(): array
    {
        $items = $this->dicomNonReconcilieRepository->findEnAttente();

        return array_map(fn (DicomNonReconcilie $item) => [
            'id' => $item->getId(),
            'orthancInstanceId' => $item->getOrthancInstanceId(),
            'patientIdDicom' => $item->getPatientIdDicom(),
            'patientNomDicom' => $item->getPatientNomDicom(),
            'studyInstanceUid' => $item->getStudyInstanceUid(),
            'modality' => $item->getModality(),
            'statut' => $item->getStatut(),
            'receivedAt' => $item->getReceivedAt()->format(\DateTimeInterface::ATOM),
            'candidats' => array_map(
                fn (Examen $examen) => $this->examenService->toArray($examen),
                $this->resolveCandidates($item)
            ),
        ], $items);
    }

    private function resolveCandidates(DicomNonReconcilie $item): array
    {
        if ($item->getStudyInstanceUid()) {
            $examen = $this->examenRepository->findOneBy(['studyInstanceUid' => $item->getStudyInstanceUid()]);
            if ($examen) {
                return [$examen];
            }
        }

        if ($item->getPatientIdDicom()) {
            $examens = $this->examenRepository->findByPatientIdentifier($item->getPatientIdDicom());
            if (!empty($examens)) {
                return $examens;
            }
        }

        if ($item->getPatientNomDicom()) {
            [$nom, $prenom] = $this->splitDicomPatientName($item->getPatientNomDicom());
            if ($nom !== '') {
                $examens = $this->examenRepository->findByPatientNomPrenomEnCours($nom, $prenom ?: null);
                if (!empty($examens)) {
                    return $examens;
                }
            }
        }

        return [];
    }

    private function splitDicomPatientName(string $patientNomDicom): array
    {
        $parts = array_map('trim', explode('^', $patientNomDicom, 2));
        $nom = $parts[0] ?? '';
        $prenom = $parts[1] ?? '';

        return [$nom, $prenom];
    }
}
