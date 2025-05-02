<?php

namespace App\Repository;

use App\Entity\Registro;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Registro>
 */
class RegistroRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Registro::class);
    }

    public function lastRegistro(int $pac_id): array
    {
        $query = $this->createQueryBuilder('r')
            ->addSelect('c')
            ->addSelect('d')
            ->addSelect('m')
            ->addSelect('di')
            ->addSelect('dr')
            ->leftJoin('r.cv_id', 'c')
            ->leftJoin('r.die_id', 'd')
            ->leftJoin('r.mov_id', 'm')
            ->leftJoin('r.dia_id', 'di')
            ->leftJoin('r.dre_id', 'dr')
            ->andWhere('r.pac_id = :pacId')
            ->setParameter('pacId', $pac_id)
            ->orderBy('r.reg_timestamp', 'DESC')
            ->setMaxResults(1)
            ->getQuery();

        $registro = $query->getOneOrNullResult();

        return [
            'lastRegistro' => $registro
        ];
    }

    //    /**
    //     * @return Registro[] Returns an array of Registro objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('r.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Registro
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
