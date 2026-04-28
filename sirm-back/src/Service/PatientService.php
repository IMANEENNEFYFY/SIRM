<?php
namespace App\Service;

use App\Entity\Patient;
use App\Repository\PatientRepository;
use Doctrine\ORM\EntityManagerInterface;

class PatientService
{
    public function __construct(
        private EntityManagerInterface $em,
        private PatientRepository $patientRepository
    ) {}

    public function genererPatientId(\DateTimeInterface $dateNaissance): string
    {
        $date = $dateNaissance->format('Ymd');
        $suffixe = strtoupper(substr(uniqid(), -4));
        return "P-{$date}-{$suffixe}";
    }

    public function creerPatient(array $data): Patient
    {
        // Vérifier unicité CIN
        $existing = $this->patientRepository->findOneBy(['cin' => $data['cin']]);
        if ($existing) {
            throw new \RuntimeException('CIN déjà existant', 409);
        }

        $patient = new Patient();
        $patient->setNom($data['nom']);
        $patient->setPrenom($data['prenom']);
        $patient->setCin($data['cin']);
        $patient->setSexe($data['sexe']);
        $patient->setDateNaissance(new \DateTime($data['dateNaissance']));
        $patient->setTelephone($data['telephone'] ?? null);
        $patient->setAdresse($data['adresse'] ?? null);
        $patient->setPatientId(
            $this->genererPatientId(new \DateTime($data['dateNaissance']))
        );

        $this->em->persist($patient);
        $this->em->flush();

        return $patient;
    }

    public function modifierPatient(Patient $patient, array $data): Patient
    {
        if (isset($data['nom']))       $patient->setNom($data['nom']);
        if (isset($data['prenom']))    $patient->setPrenom($data['prenom']);
        if (isset($data['telephone'])) $patient->setTelephone($data['telephone']);
        if (isset($data['adresse']))   $patient->setAdresse($data['adresse']);

        $this->em->flush();
        return $patient;
    }

    public function archiverPatient(Patient $patient): Patient
    {
        $patient->setActif(false);
        $this->em->flush();
        return $patient;
    }

    public function toArray(Patient $patient): array
    {
        return [
            'id'             => $patient->getId(),
            'patientId'      => $patient->getPatientId(),
            'nom'            => $patient->getNom(),
            'prenom'         => $patient->getPrenom(),
            'nomComplet'     => $patient->getNomComplet(),
            'cin'            => $patient->getCin(),
            'dateNaissance'  => $patient->getDateNaissance()->format('Y-m-d'),
            'age'            => $patient->getAge(),
            'sexe'           => $patient->getSexe(),
            'telephone'      => $patient->getTelephone(),
            'adresse'        => $patient->getAdresse(),
            'actif'          => $patient->isActif(),
        ];
    }
}