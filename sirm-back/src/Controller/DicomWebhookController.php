<?php
namespace App\Controller;

use App\Service\OrthancService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class DicomWebhookController extends AbstractController
{
    public function __construct(
        private readonly OrthancService $orthancService
    ) {}

    #[Route('/api/dicom/webhook', name: 'api_dicom_webhook', methods: ['POST'])]
    public function receive(Request $request): JsonResponse
    {
        $token = $request->headers->get('X-Orthanc-Token');
        if ($token !== $_ENV['ORTHANC_WEBHOOK_SECRET']) {
            return new JsonResponse(['error' => 'Unauthorized'], 401);
        }

        $data = json_decode($request->getContent(), true);
        if (!is_array($data)) {
            return new JsonResponse(['error' => 'JSON invalide'], 400);
        }

        $this->orthancService->handleNewInstance($data);

        return new JsonResponse(['status' => 'ok'], 201);
    }
}