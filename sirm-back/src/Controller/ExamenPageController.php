<?php
namespace App\Controller;

use App\Repository\ExamenRepository;
use App\Repository\MachineRepository;
use App\Repository\PatientRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ExamenPageController extends AbstractController
{
    #[Route('/', name: 'home', methods: ['GET'])]
    public function home(): Response
    {
        return $this->redirectToRoute('examen_page');
    }

    #[Route('/examens', name: 'examen_page', methods: ['GET'])]
    public function index(
        Request $request,
        PatientRepository $patientRepository,
        MachineRepository $machineRepository,
        ExamenRepository $examenRepository
    ): Response {
        $page = max(1, (int) $request->query->get('page', 1));
        $patients = $patientRepository->findBy(['actif' => true], ['nom' => 'ASC']);
        $machines = $machineRepository->findAll();
        $examens = $examenRepository->findAllWithRelations($page);
        $types = array_values(array_unique(array_map(fn($machine) => $machine->getModalite(), $machines)));
        sort($types);

        return $this->render('examen/index.html.twig', [
            'page' => $page,
            'patients' => $patients,
            'machines' => $machines,
            'examens' => $examens,
            'types' => $types,
        ]);
    }
}
