<?php

namespace App\Controller;

use App\Entity\Machine;
use App\Enum\StatutMachine;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/machines')]
class MachineController extends AbstractController
{
    #[Route('', methods: ['GET'])]
    public function getAll(EntityManagerInterface $em): JsonResponse
    {
        $machines = $em->getRepository(Machine::class)->findAll();

        return $this->json(array_map(fn (Machine $machine) => $this->serializeMachine($machine), $machines));
    }

    #[Route('/{id}', methods: ['GET'])]
    public function getMachine(int $id, EntityManagerInterface $em): JsonResponse
    {
        $machine = $em->getRepository(Machine::class)->find($id);
        if (!$machine) {
            return $this->json(['error' => 'Machine not found'], 404);
        }

        return $this->json($this->serializeMachine($machine));
    }

    #[Route('/{id}/statut', methods: ['PATCH'])]
    public function updateStatut(int $id, Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true) ?? [];
        $machine = $em->getRepository(Machine::class)->find($id);

        if (!$machine) {
            return $this->json(['error' => 'Machine not found'], 404);
        }

        if (!isset($data['statut'])) {
            return $this->json(['error' => 'Missing statut'], 400);
        }

        try {
            $statut = StatutMachine::from($data['statut']);
            $machine->setStatut($statut);

            if (!empty($data['dateDebut'])) {
                $machine->setDateDebut(new \DateTime($data['dateDebut']));
            }
            if (!empty($data['dateFin'])) {
                $machine->setDateFin(new \DateTime($data['dateFin']));
            }
            if (isset($data['description'])) {
                $machine->setDescription($data['description']);
            }

            $em->flush();

            return $this->json([
                'id' => $machine->getId(),
                'statut' => $machine->getStatut()->value,
                'message' => 'Machine status updated',
            ]);
        } catch (\ValueError $e) {
            return $this->json(['error' => 'Invalid statut value'], 400);
        }
    }

    private function serializeMachine(Machine $machine): array
    {
        return [
            'id' => $machine->getId(),
            'nom' => $machine->getNom(),
            'modalite' => $machine->getModalite(),
            'aeTitle' => $machine->getAeTitle(),
            'statut' => $machine->getStatut()->value,
            'couleur' => $this->getStatutColor($machine->getStatut()),
            'isDisponible' => $machine->isDisponible(),
            'dateDebut' => $machine->getDateDebut()?->format('Y-m-d H:i:s'),
            'dateFin' => $machine->getDateFin()?->format('Y-m-d H:i:s'),
            'description' => $machine->getDescription(),
        ];
    }

    private function getStatutColor(StatutMachine $statut): string
    {
        return match ($statut) {
            StatutMachine::DISPONIBLE => 'success',
            StatutMachine::EN_COURS => 'warning',
            StatutMachine::FAIT => 'danger',
            StatutMachine::EN_MAINTENANCE => 'info',
            StatutMachine::HORS_SERVICE => 'secondary',
        };
    }
}
