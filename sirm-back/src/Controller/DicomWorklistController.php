<?php
namespace App\Controller;

use App\Repository\ExamenRepository;
use App\Service\ExamenService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class DicomWorklistController extends AbstractController
{
    public function __construct(
        private readonly ExamenRepository $examenRepository,
        private readonly ExamenService $examenService,
    ) {}

    #[Route('/api/dicom/worklist', name: 'api_dicom_worklist', methods: ['GET'])]
    public function worklist(Request $request): JsonResponse
    {
        $aeTitle = $request->query->get('aeTitle');
        $modalite = $request->query->get('modalite');
        $page = max(1, (int) $request->query->get('page', 1));
        $limit = min(100, max(1, (int) $request->query->get('limit', 50)));
        $offset = ($page - 1) * $limit;

        if (!$aeTitle && !$modalite) {
            return $this->json([
                'error' => 'Paramètre aeTitle ou modalite requis',
            ], 422);
        }

        $examens = $aeTitle
            ? $this->examenRepository->findPlannedByMachineAeTitle($aeTitle, $limit, $offset)
            : $this->examenRepository->findPlannedByModalite($modalite, $limit, $offset);

        return $this->json(array_map(
            fn($examen) => $this->examenService->toArray($examen),
            $examens
        ));
    }
}
