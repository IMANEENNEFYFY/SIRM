<?php

namespace App\Repository;

use App\Entity\DicomNonReconcilie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class DicomNonReconcilieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DicomNonReconcilie::class);
    }

    /**
     * Tous les enregistrements en attente de réconciliation
     */
    public function findEnAttente(): array
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.statut = :statut')
            ->setParameter('statut', 'EN_ATTENTE')
            ->orderBy('d.receivedAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Recherche par ID instance Orthanc
     */
    public function findByOrthancInstanceId(string $instanceId): ?DicomNonReconcilie
    {
        return $this->findOneBy(['orthancInstanceId' => $instanceId]);
    }

    /**
     * Recherche par PatientID DICOM (pour tentative de réconciliation manuelle)
     */
    public function findByPatientIdDicom(string $patientId): array
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.patientIdDicom = :patientId')
            ->setParameter('patientId', $patientId)
            ->orderBy('d.receivedAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Recherche par nom patient DICOM (recherche partielle, insensible à la casse)
     */
    public function findByPatientNom(string $nom): array
    {
        return $this->createQueryBuilder('d')
            ->andWhere('LOWER(d.patientNomDicom) LIKE LOWER(:nom)')
            ->setParameter('nom', '%' . $nom . '%')
            ->orderBy('d.receivedAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Recherche par StudyInstanceUID
     */
    public function findByStudyUid(string $studyUid): ?DicomNonReconcilie
    {
        return $this->findOneBy(['studyInstanceUid' => $studyUid]);
    }

    /**
     * Vérifie si une instance existe déjà (anti-doublon)
     */
    public function existsByOrthancInstanceId(string $instanceId): bool
    {
        return (bool) $this->createQueryBuilder('d')
            ->select('COUNT(d.id)')
            ->andWhere('d.orthancInstanceId = :instanceId')
            ->setParameter('instanceId', $instanceId)
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * Réconciliations récentes (statut RECONCILIE)
     */
    public function findReconcilies(int $limit = 20): array
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.statut = :statut')
            ->setParameter('statut', 'RECONCILIE')
            ->orderBy('d.receivedAt', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    /**
     * Instances rejetées
     */
    public function findRejetes(): array
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.statut = :statut')
            ->setParameter('statut', 'REJETE')
            ->orderBy('d.receivedAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Comptage par statut (pour dashboard)
     */
    public function countParStatut(): array
    {
        return $this->createQueryBuilder('d')
            ->select('d.statut, COUNT(d.id) as total')
            ->groupBy('d.statut')
            ->getQuery()
            ->getResult();
    }

    /**
     * Instances non réconciliées reçues entre deux dates
     */
    public function findByPeriode(\DateTimeImmutable $debut, \DateTimeImmutable $fin): array
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.receivedAt >= :debut')
            ->andWhere('d.receivedAt <= :fin')
            ->setParameter('debut', $debut)
            ->setParameter('fin', $fin)
            ->orderBy('d.receivedAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Recherche combinée nom + statut (pour interface de réconciliation manuelle)
     */
    public function findPourReconciliation(string $recherche): array
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.statut = :statut')
            ->andWhere(
                'd.patientNomDicom LIKE :recherche OR d.patientIdDicom LIKE :recherche'
            )
            ->setParameter('statut', 'EN_ATTENTE')
            ->setParameter('recherche', '%' . $recherche . '%')
            ->orderBy('d.receivedAt', 'DESC')
            ->getQuery()
            ->getResult();
    }
}