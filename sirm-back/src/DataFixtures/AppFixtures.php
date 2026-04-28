<?php
namespace App\DataFixtures;

use App\Entity\Examen;
use App\Entity\Patient;
use App\Entity\Utilisateur;
use App\Entity\Machine;
use App\Enum\Role;
use App\Enum\StatutExamen;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(
        private UserPasswordHasherInterface $hasher
    ) {}

    public function load(ObjectManager $manager): void
    {
        $admin = new Utilisateur();
        $admin->setLogin('admin');
        $admin->setNom('Admin');
        $admin->setPrenom('SIRM');
        $admin->setEmail('admin@sirm.ma');
        $admin->setRole(Role::ADMIN);
        $admin->setMotDePasse(
            $this->hasher->hashPassword($admin, 'admin123')
        );

        $manager->persist($admin);

        // Ajouter des patients de test
        $patients = [
            [
                'nom' => 'Dupont',
                'prenom' => 'Jean',
                'cin' => 'AB123456',
                'dateNaissance' => '1980-05-15',
                'sexe' => 'M',
                'telephone' => '0612345678',
                'adresse' => '123 Rue de la Santé, Casablanca'
            ],
            [
                'nom' => 'Martin',
                'prenom' => 'Marie',
                'cin' => 'CD789012',
                'dateNaissance' => '1992-08-22',
                'sexe' => 'F',
                'telephone' => '0698765432',
                'adresse' => '456 Avenue des Hôpitaux, Rabat'
            ],
            [
                'nom' => 'Benali',
                'prenom' => 'Ahmed',
                'cin' => 'EF345678',
                'dateNaissance' => '1975-12-03',
                'sexe' => 'M',
                'telephone' => '0655566677',
                'adresse' => '789 Boulevard Médical, Marrakech'
            ]
        ];

        $patientEntities = [];
        foreach ($patients as $data) {
            $patient = new Patient();
            $patient->setNom($data['nom']);
            $patient->setPrenom($data['prenom']);
            $patient->setCin($data['cin']);
            $patient->setDateNaissance(new \DateTime($data['dateNaissance']));
            $patient->setSexe($data['sexe']);
            $patient->setTelephone($data['telephone']);
            $patient->setAdresse($data['adresse']);
            $patient->setPatientId('P-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -4)));

            $manager->persist($patient);
            $patientEntities[] = $patient;
        }

        // Ajouter des machines de test
        $machines = [
            [
                'nom' => 'CT Scanner 1',
                'modalite' => 'CT',
                'aeTitle' => 'CT_SCANNER_01',
                'adresseIP' => '192.168.1.100'
            ],
            [
                'nom' => 'IRM 1',
                'modalite' => 'MR',
                'aeTitle' => 'MRI_01',
                'adresseIP' => '192.168.1.101'
            ],
            [
                'nom' => 'Radiologie Standard',
                'modalite' => 'CR',
                'aeTitle' => 'CR_01',
                'adresseIP' => '192.168.1.102'
            ],
            [
                'nom' => 'Échographie 1',
                'modalite' => 'US',
                'aeTitle' => 'US_01',
                'adresseIP' => '192.168.1.103'
            ],
            [
                'nom' => 'Radiologie Dentaire',
                'modalite' => 'DX',
                'aeTitle' => 'DX_01',
                'adresseIP' => '192.168.1.104'
            ]
        ];

        $machineEntities = [];
        foreach ($machines as $data) {
            $machine = new Machine();
            $machine->setNom($data['nom']);
            $machine->setModalite($data['modalite']);
            $machine->setAeTitle($data['aeTitle']);
            $machine->setAdresseIP($data['adresseIP']);


            $manager->persist($machine);
            $machineEntities[] = $machine;
        }

        // Ajouter des examens de test
        $examens = [
            [
                'date' => '2026-04-10 09:30:00',
                'type' => 'CT',
                'statut' => StatutExamen::PLANIFIE,
                'description' => 'Scan thoracique pour douleur thoracique.',
                'patientIndex' => 0,
                'machineIndex' => 0,
                'medecin' => $admin
            ],
            [
                'date' => '2026-04-11 11:15:00',
                'type' => 'MR',
                'statut' => StatutExamen::EN_COURS,
                'description' => 'IRM cérébrale suite à céphalées chroniques.',
                'patientIndex' => 1,
                'machineIndex' => 1,
                'medecin' => $admin
            ],
            [
                'date' => '2026-04-12 14:00:00',
                'type' => 'US',
                'statut' => StatutExamen::RECU,
                'description' => 'Échographie abdominale pour suivi de masse.',
                'patientIndex' => 2,
                'machineIndex' => 3,
                'medecin' => $admin
            ]
        ];

        foreach ($examens as $data) {
            $examen = new Examen();
            $examen->setDate(new \DateTime($data['date']));
            $examen->setType($data['type']);
            $examen->setPatient($patientEntities[$data['patientIndex']]);
            $examen->setMachine($machineEntities[$data['machineIndex']]);
            $examen->setMedecin($data['medecin']);
            $examen->setDescription($data['description']);
            $examen->updateStatut($data['statut']);

            $manager->persist($examen);
        }

        $manager->flush();
    }
}