<?php
namespace App\Controller;

use App\Repository\PatientRepository;
use App\Service\PatientService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/patients')]
class PatientController extends AbstractController
{
    public function __construct(
        private PatientService $patientService,
        private PatientRepository $patientRepository
    ) {}

    // POST /api/patients — Créer un patient
    #[Route('', name: 'patient_create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $required = ['nom', 'prenom', 'cin', 'dateNaissance', 'sexe'];
        foreach ($required as $field) {
            if (empty($data[$field])) {
                return $this->json(['error' => "Le champ '$field' est requis"], 422);
            }
        }

        try {
            $patient = $this->patientService->creerPatient($data);
            return $this->json($this->patientService->toArray($patient), 201);
        } catch (\RuntimeException $e) {
            return $this->json(['error' => $e->getMessage()], $e->getCode() ?: 400);
        }
    }

    // GET /api/patients — Lister / Rechercher
    #[Route('', name: 'patient_list', methods: ['GET'])]
    public function list(Request $request): JsonResponse
    {
        $q     = $request->query->get('q', '');
        $page  = max(1, (int) $request->query->get('page', 1));
        $offset = max(0, ($page - 1) * 10);

        if ($q) {
            $patients = $this->patientRepository->rechercher($q, $page);
        } else {
            $patients = $this->patientRepository->findBy(
                ['actif' => true],
                ['nom' => 'ASC'],
                10,
                $offset
            );

            if (empty($patients)) {
                $patients = $this->patientRepository->findBy(
                    [],
                    ['nom' => 'ASC'],
                    10,
                    $offset
                );
            }
        }

        return $this->json(array_map(
            fn($p) => $this->patientService->toArray($p),
            $patients
        ));
    }

    // GET /api/patients/{id} — Voir un patient
    #[Route('/{id}', name: 'patient_show', methods: ['GET'])]
    public function show(int $id): JsonResponse
    {
        $patient = $this->patientRepository->find($id);
        if (!$patient) {
            return $this->json(['error' => 'Patient introuvable'], 404);
        }

        return $this->json($this->patientService->toArray($patient));
    }

    // PUT /api/patients/{id} — Modifier un patient
    #[Route('/{id}', name: 'patient_update', methods: ['PUT'])]
    public function update(int $id, Request $request): JsonResponse
    {
        $patient = $this->patientRepository->find($id);
        if (!$patient) {
            return $this->json(['error' => 'Patient introuvable'], 404);
        }

        $data = json_decode($request->getContent(), true);
        $patient = $this->patientService->modifierPatient($patient, $data);

        return $this->json($this->patientService->toArray($patient));
    }

    // PATCH /api/patients/{id}/archiver — Archiver un patient
    #[Route('/{id}/archiver', name: 'patient_archive', methods: ['PATCH'])]
    public function archiver(int $id): JsonResponse
    {
        $patient = $this->patientRepository->find($id);
        if (!$patient) {
            return $this->json(['error' => 'Patient introuvable'], 404);
        }

        $this->patientService->archiverPatient($patient);

        return $this->json(['message' => 'Patient archivé avec succès']);
    }
}
