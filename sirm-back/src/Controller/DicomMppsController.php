<?php
namespace App\Controller;

use App\Enum\StatutExamen;
use App\Repository\ExamenRepository;
use App\Service\ExamenService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class DicomMppsController extends AbstractController
{
    public function __construct(
        private readonly ExamenRepository $examenRepository,
        private readonly ExamenService $examenService,
        private readonly EntityManagerInterface $em,
    ) {}

    #[Route('/api/dicom/mpps', name: 'api_dicom_mpps', methods: ['POST'])]
    public function update(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        if (!is_array($data)) {
            return new JsonResponse(['error' => 'JSON invalide'], 400);
        }

        $studyUid = $data['studyInstanceUID'] ?? null;
        $status = strtoupper(trim($data['status'] ?? ''));

        if (!$studyUid || !$status) {
            return new JsonResponse(['error' => 'studyInstanceUID et status requis'], 422);
        }

        $examen = $this->examenRepository->findOneBy(['studyInstanceUid' => $studyUid]);
        if (!$examen) {
            return new JsonResponse(['error' => 'Examen non trouvé'], 404);
        }

        try {
            if (in_array($status, ['STARTED', 'IN PROGRESS', 'IN_PROGRESS'], true)) {
                $this->examenService->modifierStatut($examen, StatutExamen::EN_COURS);
            } elseif (in_array($status, ['COMPLETED', 'FINISHED', 'DONE', 'N-CREATE', 'N-SET'], true)) {
                if ($examen->getStatut() === StatutExamen::PLANIFIE) {
                    $examen->updateStatut(StatutExamen::RECU);
                } else {
                    $this->examenService->modifierStatut($examen, StatutExamen::RECU);
                }
            } else {
                return new JsonResponse(['error' => 'Status MPPS non supporté'], 422);
            }

            $this->em->flush();
            return new JsonResponse(['status' => 'ok', 'examenId' => $examen->getId(), 'statut' => $examen->getStatut()->value]);
        } catch (\RuntimeException $e) {
            return new JsonResponse(['error' => $e->getMessage()], 400);
        }
    }
}
