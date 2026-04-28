<?php

namespace App\Repository;

use App\Entity\ResultatDicom;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ResultatDicomRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ResultatDicom::class);
    }

    /**
     * Trouve un résultat par son ID d'instance Orthanc
     */
    public function findByOrthancInstanceId(string $instanceId): ?ResultatDicom
    {
        return $this->findOneBy(['orthancInstanceId' => $instanceId]);
    }

    /**
     * Tous les résultats d'un examen donné
     */
    public function findByExamen(int $examenId): array
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.examen = :examenId')
            ->setParameter('examenId', $examenId)
            ->orderBy('r.receivedAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Dernier résultat DICOM par examen pour un ensemble d'examens.
     *
     * @param int[] $examenIds
     * @return array<int, ResultatDicom> map [examenId => ResultatDicom]
     */
    public function findLatestByExamenIds(array $examenIds): array
    {
        if (empty($examenIds)) {
            return [];
        }

        $rows = $this->createQueryBuilder('r')
            ->addSelect('e')
            ->join('r.examen', 'e')
            ->andWhere('e.id IN (:ids)')
            ->setParameter('ids', $examenIds)
            ->orderBy('e.id', 'ASC')
            ->addOrderBy('r.receivedAt', 'DESC')
            ->getQuery()
            ->getResult();

        $latestByExamen = [];
        foreach ($rows as $resultat) {
            $examenId = $resultat->getExamen()?->getId();
            if (!$examenId || isset($latestByExamen[$examenId])) {
                continue;
            }

            $latestByExamen[$examenId] = $resultat;
        }

        return $latestByExamen;
    }

    /**
     * Résultats reçus entre deux dates
     */
    public function findByPeriode(\DateTimeImmutable $debut, \DateTimeImmutable $fin): array
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.receivedAt >= :debut')
            ->andWhere('r.receivedAt <= :fin')
            ->setParameter('debut', $debut)
            ->setParameter('fin', $fin)
            ->orderBy('r.receivedAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Résultats par modalité (CT, MRI, RX...)
     */
    public function findByModalite(string $modality): array
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.modality = :modality')
            ->setParameter('modality', $modality)
            ->orderBy('r.receivedAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Derniers résultats reçus (pour dashboard)
     */
    public function findDerniers(int $limit = 10): array
    {
        return $this->createQueryBuilder('r')
            ->join('r.examen', 'e')
            ->addSelect('e')
            ->orderBy('r.receivedAt', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    /**
     * Vérifie si une instance Orthanc existe déjà (anti-doublon)
     */
    public function existsByOrthancInstanceId(string $instanceId): bool
    {
        return (bool) $this->createQueryBuilder('r')
            ->select('COUNT(r.id)')
            ->andWhere('r.orthancInstanceId = :instanceId')
            ->setParameter('instanceId', $instanceId)
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * Statistiques par modalité
     */
    public function countParModalite(): array
    {
        return $this->createQueryBuilder('r')
            ->select('r.modality, COUNT(r.id) as total')
            ->groupBy('r.modality')
            ->orderBy('total', 'DESC')
            ->getQuery()
            ->getResult();
    }
}