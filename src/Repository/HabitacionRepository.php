<?php

namespace App\Repository;

use App\Entity\Habitacion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Habitacion>
 */
class HabitacionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Habitacion::class);
    }

    public function findPaginated(int $page, int $limit): array
    {

        $query = $this->createQueryBuilder('h')
            ->leftJoin('h.pac_id', 'p')
            ->addSelect('p')
            ->orderBy('h.hab_id', 'ASC')
            ->getQuery()
            ->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit);

        $paginator = new Paginator($query);
        $totalRooms = count($paginator);

        $rooms = $query->getResult();

        return [
            'rooms' => $rooms,
            'totalRooms' => $totalRooms,
        ];
    }

    // public function showRoom(string $room_id)
    // {
    //     $room = $this->findBy(['hab_id' => $room_id]);

    //     return [
    //         'room' => $room
    //     ];
    // }

    //    /**
    //     * @return Habitacion[] Returns an array of Habitacion objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('h')
    //            ->andWhere('h.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('h.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Habitacion
    //    {
    //        return $this->createQueryBuilder('h')
    //            ->andWhere('h.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
