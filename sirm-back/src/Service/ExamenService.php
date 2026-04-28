<?php
namespace App\Service;

use App\Entity\Examen;
use App\Entity\Patient;
use App\Entity\Machine;
use App\Entity\ResultatDicom;
use App\Entity\Utilisateur;
use App\Enum\StatutExamen;
use App\Repository\ExamenRepository;
use App\Repository\ResultatDicomRepository;
use Doctrine\ORM\EntityManagerInterface;

class ExamenService
{
    public function __construct(
        private EntityManagerInterface $em,
        private ExamenRepository $examenRepository,
        private ResultatDicomRepository $resultatDicomRepository
    ) {}

    public function creerExamen(array $data): Examen
    {
        // Utiliser find() au lieu de getReference()
        $patient = $this->em->getRepository(Patient::class)->find($data['patientId']);
        if (!$patient) {
            throw new \RuntimeException('Patient introuvable', 404);
        }

        $machine = $this->em->getRepository(Machine::class)->find($data['machineId']);
        if (!$machine) {
            throw new \RuntimeException('Machine introuvable', 404);
        }

        // Vérifier la disponibilité de la machine
        if (!$machine->isDisponible()) {
            throw new \RuntimeException(
                "Machine non disponible. Statut actuel: {$machine->getStatut()->value}",
                422
            );
        }

        $examen = new Examen();
        $examen->setType($data['type']);
        $examen->setDescription($data['description'] ?? null);
        if (!empty($data['date'])) {
            $examen->setDate(new \DateTime($data['date']));
        }
        $examen->setPatient($patient);
        $examen->setMachine($machine);

        if (isset($data['medecinId'])) {
            $medecin = $this->em->getRepository(Utilisateur::class)->find($data['medecinId']);
            if ($medecin) {
                $examen->setMedecin($medecin);
            }
        }

        $this->em->persist($examen);
        $this->em->flush();

        return $examen;
    }

    public function modifierExamen(Examen $examen, array $data): Examen
    {
        if (isset($data['patientId'])) {
            $patient = $this->em->getRepository(Patient::class)->find($data['patientId']);
            if (!$patient) {
                throw new \RuntimeException('Patient introuvable', 404);
            }
            $examen->setPatient($patient);
        }

        if (isset($data['machineId'])) {
            $machine = $this->em->getRepository(Machine::class)->find($data['machineId']);
            if (!$machine) {
                throw new \RuntimeException('Machine introuvable', 404);
            }
            $examen->setMachine($machine);
        }

        if (array_key_exists('type', $data) && !empty($data['type'])) {
            $examen->setType($data['type']);
        }

        if (array_key_exists('description', $data)) {
            $examen->setDescription($data['description'] ?: null);
        }

        if (!empty($data['date'])) {
            $examen->setDate(new \DateTime($data['date']));
        }

        $this->em->flush();

        return $examen;
    }

    public function modifierStatut(Examen $examen, StatutExamen $nouveauStatut): Examen
    {
        $ancien = $examen->getStatut();

        if (!$this->isValidTransition($ancien, $nouveauStatut)) {
            throw new \RuntimeException(
                "Transition impossible: {$ancien->value} → {$nouveauStatut->value}", 400
            );
        }

        $examen->updateStatut($nouveauStatut);
        
        $machine = $examen->getMachine();
        
        // Gérer le statut de la machine en fonction du nouvel état de l'examen
        if ($nouveauStatut === StatutExamen::EN_COURS) {
            // Machine occupée
            $machine->setStatut(\App\Enum\StatutMachine::EN_COURS);
            $machine->setDateDebut(new \DateTime());
        } elseif ($nouveauStatut === StatutExamen::RECU) {
            // Examen complété - marquer comme fait
            $machine->setStatut(\App\Enum\StatutMachine::FAIT);
            $machine->setDateFin(new \DateTime());
        } elseif ($nouveauStatut === StatutExamen::ANNULE) {
            // Examen annulé - libérer la machine
            $machine->setStatut(\App\Enum\StatutMachine::DISPONIBLE);
            $machine->setDateDebut(null);
            $machine->setDateFin(null);
        }
        
        $this->em->flush();

        return $examen;
    }

    private function isValidTransition(StatutExamen $ancien, StatutExamen $nouveau): bool
    {
        $transitions = [
            StatutExamen::PLANIFIE->value => [StatutExamen::EN_COURS, StatutExamen::RECU, StatutExamen::ANNULE],
            StatutExamen::EN_COURS->value => [StatutExamen::RECU, StatutExamen::ANNULE],
            StatutExamen::RECU->value     => [],
            StatutExamen::ANNULE->value   => [],
        ];

        return in_array($nouveau, $transitions[$ancien->value] ?? [], true);
    }

    public function annulerExamen(Examen $examen): Examen
    {
        if ($examen->getStatut() === StatutExamen::RECU) {
            throw new \RuntimeException('Impossible d\'annuler un examen reçu', 400);
        }
        if ($examen->getStatut() === StatutExamen::ANNULE) {
            throw new \RuntimeException('Examen déjà annulé', 400);
        }

        $examen->updateStatut(StatutExamen::ANNULE);
        $machine = $examen->getMachine();
        $machine->setStatut(\App\Enum\StatutMachine::DISPONIBLE);
        $machine->setDateDebut(null);
        $machine->setDateFin(null);
        $this->em->flush();

        return $examen;
    }

    public function recreerExamen(Examen $source): Examen
    {
        $machine = $source->getMachine();
        if (!$machine->isDisponible()) {
            throw new \RuntimeException(
                "Machine non disponible. Statut actuel: {$machine->getStatut()->value}",
                422
            );
        }

        $examen = new Examen();
        $examen->setType($source->getType());
        $examen->setDescription($source->getDescription());
        $examen->setCompteRendu($source->getCompteRendu());
        $examen->setDate($source->getDate());
        $examen->setDatePrevue($source->getDatePrevue());
        $examen->setPatient($source->getPatient());
        $examen->setMachine($source->getMachine());
        $examen->setMedecin($source->getMedecin());

        $this->em->persist($examen);
        $this->em->flush();

        return $examen;
    }

    public function lierResultatDicom(Examen $examen, array $payload): ResultatDicom
    {
        $instanceId = $payload['orthancInstanceId'] ?? null;
        if (!$instanceId) {
            throw new \RuntimeException("orthancInstanceId manquant", 422);
        }

        $resultat = new ResultatDicom();
        $resultat->setExamen($examen);
        $resultat->setOrthancInstanceId($instanceId);
        $resultat->setStudyInstanceUid($payload['studyInstanceUid'] ?? ($examen->getStudyInstanceUid() ?? ''));
        $resultat->setSeriesInstanceUid($payload['seriesInstanceUid'] ?? null);
        $resultat->setModality($payload['modality'] ?? null);
        $resultat->setOrthancUrl($payload['orthancUrl'] ?? ('http://localhost:8042/instances/' . $instanceId));

        if (!empty($payload['studyInstanceUid'])) {
            $examen->setStudyInstanceUid($payload['studyInstanceUid']);
        }

        if ($examen->getStatut() !== StatutExamen::RECU) {
            $this->modifierStatut($examen, StatutExamen::RECU);
        }

        $this->em->persist($resultat);
        $this->em->flush();

        return $resultat;
    }

    /**
     * @param Examen[] $examens
     * @return array<int, array<string, mixed>>
     */
    public function toArrayMany(array $examens): array
    {
        if (empty($examens)) {
            return [];
        }

        $examenIds = array_map(fn (Examen $examen) => $examen->getId(), $examens);
        $latestByExamen = $this->resultatDicomRepository->findLatestByExamenIds($examenIds);

        return array_map(
            fn (Examen $examen) => $this->buildExamenPayload($examen, $latestByExamen[$examen->getId()] ?? null),
            $examens
        );
    }

    public function toArray(Examen $examen): array
    {
        $resultatDicom = $this->resultatDicomRepository->findOneBy(
            ['examen' => $examen],
            ['receivedAt' => 'DESC']
        );

        return $this->buildExamenPayload($examen, $resultatDicom);
    }

    private function buildExamenPayload(Examen $examen, ?ResultatDicom $resultatDicom): array
    {
        return [
            'id'              => $examen->getId(),
            'accessionNumber' => $examen->getAccessionNumber(),
            'date'            => $examen->getDate()->format(\DateTimeInterface::ATOM),
            'type'            => $examen->getType(),
            'statut'          => $examen->getStatut()->value,
            'description'     => $examen->getDescription(),
            'compteRendu'     => $examen->getCompteRendu(),
            'studyInstanceUid'=> $examen->getStudyInstanceUid(),
            'dateModifStatut' => $examen->getDateModifStatut()?->format(\DateTimeInterface::ATOM),
            'patient'         => [
                'id'            => $examen->getPatient()->getId(),
                'nom'           => $examen->getPatient()->getNom(),
                'prenom'        => $examen->getPatient()->getPrenom(),
                'patientId'     => $examen->getPatient()->getPatientId(),
                'dateNaissance' => $examen->getPatient()->getDateNaissance()?->format('Y-m-d'),
            ],
            'machine'         => [
                'id'            => $examen->getMachine()->getId(),
                'nom'           => $examen->getMachine()->getNom(),
                'modalite'      => $examen->getMachine()->getModalite(),
                'aeTitle'       => $examen->getMachine()->getAeTitle(),
                'statut'        => $examen->getMachine()->getStatut()->value,
                'isDisponible'  => $examen->getMachine()->isDisponible(),
            ],
            'medecin'         => $examen->getMedecin() ? [
                'id'  => $examen->getMedecin()->getId(),
                'nom' => $examen->getMedecin()->getNom(),
            ] : null,
            'resultatDicom'   => $resultatDicom ? [
                'id' => $resultatDicom->getId(),
                'orthancInstanceId' => $resultatDicom->getOrthancInstanceId(),
                'studyInstanceUid' => $resultatDicom->getStudyInstanceUid(),
                'modality' => $resultatDicom->getModality(),
                'orthancUrl' => $resultatDicom->getOrthancUrl(),
                'receivedAt' => $resultatDicom->getReceivedAt()->format(\DateTimeInterface::ATOM),
            ] : null,
        ];
    }
}
