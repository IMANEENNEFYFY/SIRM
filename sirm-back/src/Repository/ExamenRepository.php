<?php
namespace App\Repository;

use App\Entity\Examen;
use App\Enum\StatutExamen;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ExamenRepository extends ServiceEntityRepository
{
    private function computeOffset(int $page, int $limit): int
    {
        return max(0, ($page - 1) * $limit);
    }

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Examen::class);
    }

    public function findByPatient(int $patientId, int $page = 1, int $limit = 20): array
    {
        $offset = $this->computeOffset($page, $limit);

        return $this->createQueryBuilder('e')
            ->select('e', 'p', 'm')
            ->leftJoin('e.patient', 'p')
            ->leftJoin('e.machine', 'm')
            ->where('p.id = :patientId')
            ->setParameter('patientId', $patientId)
            ->orderBy('e.date', 'DESC')
            ->setMaxResults($limit)
            ->setFirstResult($offset)
            ->getQuery()
            ->getResult();
    }

    public function findByStatut(StatutExamen $statut, int $page = 1, int $limit = 20): array
    {
        $offset = $this->computeOffset($page, $limit);

        return $this->createQueryBuilder('e')
            ->select('e', 'p', 'm')
            ->leftJoin('e.patient', 'p')
            ->leftJoin('e.machine', 'm')
            ->where('e.statut = :statut')
            ->setParameter('statut', $statut)
            ->orderBy('e.date', 'DESC')
            ->setMaxResults($limit)
            ->setFirstResult($offset)
            ->getQuery()
            ->getResult();
    }

    public function countByStatut(StatutExamen $statut): int
    {
        return (int) $this->createQueryBuilder('e')
            ->select('COUNT(e.id)')
            ->where('e.statut = :statut')
            ->setParameter('statut', $statut)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function findPlannedByMachineAeTitle(string $aeTitle, int $limit = 50, int $offset = 0): array
    {
        return $this->createQueryBuilder('e')
            ->select('e', 'p', 'm')
            ->join('e.machine', 'm')
            ->leftJoin('e.patient', 'p')
            ->where('m.aeTitle = :aeTitle')
            ->andWhere('e.statut = :statut')
            ->setParameter('aeTitle', $aeTitle)
            ->setParameter('statut', StatutExamen::PLANIFIE)
            ->orderBy('e.date', 'ASC')
            ->setMaxResults($limit)
            ->setFirstResult($offset)
            ->getQuery()
            ->getResult();
    }

    public function findPlannedByModalite(string $modalite, int $limit = 50, int $offset = 0): array
    {
        return $this->createQueryBuilder('e')
            ->join('e.machine', 'm')
            ->where('m.modalite = :modalite')
            ->andWhere('e.statut = :statut')
            ->setParameter('modalite', $modalite)
            ->setParameter('statut', StatutExamen::PLANIFIE)
            ->orderBy('e.date', 'ASC')
            ->setMaxResults($limit)
            ->setFirstResult($offset)
            ->getQuery()
            ->getResult();
    }

    public function findExamenEnCoursByPatientId(string $patientIdDicom): ?Examen
    {
        return $this->createQueryBuilder('e')
            ->join('e.patient', 'p')
            ->where('p.patientId = :val') // Utilisation du bon nom de champ : patientId
            ->andWhere('e.statut = :statut')
            ->setParameter('val', $patientIdDicom)
            ->setParameter('statut', StatutExamen::EN_COURS)
            ->orderBy('e.date', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findByPatientIdentifier(string $patientIdentifier): array
    {
        return $this->createQueryBuilder('e')
            ->select('e', 'p', 'm')
            ->innerJoin('e.patient', 'p')
            ->leftJoin('e.machine', 'm')
            ->where('p.patientId = :identifier')
            ->andWhere('e.statut = :statut')
            ->setParameter('identifier', $patientIdentifier)
            ->setParameter('statut', StatutExamen::EN_COURS)
            ->getQuery()
            ->getResult();
    }

    public function findByPatientNomPrenomEnCours(string $nom, ?string $prenom = null): array
    {
        $qb = $this->createQueryBuilder('e')
            ->select('e', 'p', 'm')
            ->innerJoin('e.patient', 'p')
            ->leftJoin('e.machine', 'm')
            ->where('LOWER(p.nom) = LOWER(:nom)')
            ->andWhere('e.statut = :statut')
            ->setParameter('nom', $nom)
            ->setParameter('statut', StatutExamen::EN_COURS);

        if ($prenom) {
            $qb->andWhere('LOWER(p.prenom) = LOWER(:prenom)')
                ->setParameter('prenom', $prenom);
        }

        return $qb->getQuery()->getResult();
    }

    public function findAllWithRelations(int $page = 1, int $limit = 20): array
    {
        $offset = $this->computeOffset($page, $limit);

        return $this->createQueryBuilder('e')
            ->select('e', 'p', 'm')
            ->leftJoin('e.patient', 'p')
            ->leftJoin('e.machine', 'm')
            ->orderBy('e.date', 'DESC')
            ->setMaxResults($limit)
            ->setFirstResult($offset)
            ->getQuery()
            ->getResult();
    }

    public function findOneByStudyInstanceUidWithRelations(string $studyInstanceUid): ?Examen
    {
        return $this->createQueryBuilder('e')
            ->select('e', 'p', 'm')
            ->leftJoin('e.patient', 'p')
            ->leftJoin('e.machine', 'm')
            ->where('e.studyInstanceUid = :studyInstanceUid')
            ->setParameter('studyInstanceUid', $studyInstanceUid)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findWithDicomResults(int $page = 1, int $limit = 20): array
    {
        $offset = $this->computeOffset($page, $limit);

        return $this->createQueryBuilder('e')
            ->select('e', 'p', 'm')
            ->leftJoin('e.patient', 'p')
            ->leftJoin('e.machine', 'm')
            ->leftJoin('e.resultatsDicom', 'r')
            ->where('e.statut = :recu OR r.id IS NOT NULL')
            ->setParameter('recu', StatutExamen::RECU)
            ->orderBy('e.date', 'DESC')
            ->setMaxResults($limit)
            ->setFirstResult($offset)
            ->getQuery()
            ->getResult();
    }

    public function findForReconciliationSelection(int $page = 1, int $limit = 100): array
    {
        $offset = $this->computeOffset($page, $limit);

        return $this->createQueryBuilder('e')
            ->select('e', 'p', 'm')
            ->leftJoin('e.patient', 'p')
            ->leftJoin('e.machine', 'm')
            ->leftJoin('e.resultatsDicom', 'r')
            ->where('e.statut NOT IN (:excludedStatuts)')
            ->andWhere('r.id IS NULL')
            ->setParameter('excludedStatuts', [StatutExamen::RECU, StatutExamen::ANNULE])
            ->orderBy('e.date', 'DESC')
            ->setMaxResults($limit)
            ->setFirstResult($offset)
            ->getQuery()
            ->getResult();
    }

    /**
     * @return array<string, int>
     */
    public function countGroupedByStatut(): array
    {
        $rows = $this->createQueryBuilder('e')
            ->select('e.statut AS statut, COUNT(e.id) AS total')
            ->groupBy('e.statut')
            ->getQuery()
            ->getScalarResult();

        $counts = [];
        foreach ($rows as $row) {
            $statut = $row['statut'];
            if ($statut instanceof StatutExamen) {
                $statut = $statut->value;
            }

            $counts[(string) $statut] = (int) $row['total'];
        }

        return $counts;
    }
}
