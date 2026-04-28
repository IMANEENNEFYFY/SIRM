<?php
namespace App\Controller;
use App\Entity\Machine;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/machines')]
class MachineController extends AbstractController
{
    #[Route('', methods: ['GET'])]
    public function getAll(EntityManagerInterface $em): JsonResponse       
    {
        $machines = $em->getRepository(Machine::class)->findAll();

        $data = array_map(function ($m) {
            $couleur = match($m->getStatut()) {
                \App\Enum\StatutMachine::DISPONIBLE => 'success',     // vert
                \App\Enum\StatutMachine::EN_COURS => 'warning',       // orange
                \App\Enum\StatutMachine::FAIT => 'danger',            // rouge
                \App\Enum\StatutMachine::EN_MAINTENANCE => 'info',
                \App\Enum\StatutMachine::HORS_SERVICE => 'secondary',
            };

            return [
                'id' => $m->getId(),
                'nom' => $m->getNom(),
                'modalite' => $m->getModalite(),
                'aeTitle' => $m->getAeTitle(),
                'statut' => $m->getStatut()->value,
                'couleur' => $couleur,
                'isDisponible' => $m->isDisponible(),
                'dateDebut' => $m->getDateDebut()?->format('Y-m-d H:i:s'),
                'dateFin' => $m->getDateFin()?->format('Y-m-d H:i:s'),
                'description' => $m->getDescription(),
            ];
        }, $machines);

        return $this->json($data);
    }

    #[Route('/{id}', methods: ['GET'])]
    public function getMachine(int $id, EntityManagerInterface $em): JsonResponse
    {
        $machine = $em->getRepository(Machine::class)->find($id);
        if (!$machine) {
            return $this->json(['error' => 'Machine not found'], 404);
        }

        $couleur = match($machine->getStatut()) {
            \App\Enum\StatutMachine::DISPONIBLE => 'success',
            \App\Enum\StatutMachine::EN_COURS => 'warning',
            \App\Enum\StatutMachine::FAIT => 'danger',
            \App\Enum\StatutMachine::EN_MAINTENANCE => 'info',
            \App\Enum\StatutMachine::HORS_SERVICE => 'secondary',
        };

        return $this->json([
            'id' => $machine->getId(),
            'nom' => $machine->getNom(),
            'modalite' => $machine->getModalite(),
            'aeTitle' => $machine->getAeTitle(),
            'statut' => $machine->getStatut()->value,
            'couleur' => $couleur,
            'isDisponible' => $machine->isDisponible(),
            'dateDebut' => $machine->getDateDebut()?->format('Y-m-d H:i:s'),
            'dateFin' => $machine->getDateFin()?->format('Y-m-d H:i:s'),
            'description' => $machine->getDescription(),
        ]);
    }

    #[Route('/{id}/statut', methods: ['PATCH'])]
    public function updateStatut(int $id, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $machine = $em->getRepository(Machine::class)->find($id);
        
        if (!$machine) {
            return $this->json(['error' => 'Machine not found'], 404);
        }

        if (!isset($data['statut'])) {
            return $this->json(['error' => 'Missing statut'], 400);
        }

        try {
            $statut = \App\Enum\StatutMachine::from($data['statut']);
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
                'message' => 'Machine status updated'
            ]);
        } catch (\ValueError $e) {
            return $this->json(['error' => 'Invalid statut value'], 400);
        }
    }
}