<?php

namespace App\Repository;

use App\Entity\Registro;
use DateTime;
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

        $latestRegistro = $query->getOneOrNullResult();

        if (!$latestRegistro) {
            return ['lastRegistro' => null];
        }

        $DefRegistro = clone $latestRegistro;

        $previousRegistros = $this->createQueryBuilder('r')
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
            ->andWhere('r.id != :currentId')
            ->setParameter('pacId', $pac_id)
            ->setParameter('currentId', $DefRegistro->getId())
            ->orderBy('r.reg_timestamp', 'DESC')
            ->getQuery()
            ->getResult();

        if (empty($previousRegistros)) {
            return ['lastRegistro' => $DefRegistro];
        }

        $fields = [
            'cv_id' => 'getConstantesVitales',
            'die_id' => 'getDieta',
            'mov_id' => 'getMovilizacion',
            'dia_id' => 'getDiagnostico',
            'dre_id' => 'getDrenaje',
        ];

        $setters = [
            'cv_id' => 'setConstantesVitales',
            'die_id' => 'setDieta',
            'mov_id' => 'setMovilizacion',
            'dia_id' => 'setDiagnostico',
            'dre_id' => 'setDrenaje',
        ];


        foreach ($fields as $field => $getter) {
            if ($DefRegistro->$getter() === null) {
                foreach ($previousRegistros as $previousRegistro) {
                    $value = $previousRegistro->$getter();
                    if ($value !== null) {
                        $setter = $setters[$field];
                        $DefRegistro->$setter($value);
                        break;
                    }
                }
            }
        }

        return ['lastRegistro' => $DefRegistro];
    }
    /* public function lastRegistro(int $pac_id): array
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
    } */


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
    public function getCVUltimosXdias(int $pacienteId, int $dias = 7): array
    {

        $hoy = new DateTime('today');
        $inicio = (clone $hoy)->modify('-' . ($dias - 1) . ' days')->setTime(0, 0, 0);
        $fin = (clone $hoy)->setTime(23, 59, 59);

        $qb = $this->createQueryBuilder('r')
            ->innerJoin('r.cv_id', 'cv')
            ->addSelect('cv')
            ->where('r.pac_id = :pacienteId')
            ->andWhere('cv.cv_timestamp BETWEEN :inicio AND :fin')
            ->setParameter('pacienteId', $pacienteId)
            ->setParameter('inicio', $inicio)
            ->setParameter('fin', $fin)
            ->orderBy('cv.cv_timestamp', 'DESC');

        $registros = $qb->getQuery()->getResult();

        $resultados = [];

        foreach ($registros as $registro) {
            $cv = $registro->getConstantesVitales();
            if (!$cv) continue;

            $timestamp = $cv->getCvTimestamp();
            $dia = $timestamp->format('Y-m-d');
            $hora = (int) $timestamp->format('H');

            // Determinar turno
            if ($hora >= 7 && $hora < 13) {
                $turno = 'Matí';
            } elseif ($hora >= 13 && $hora < 18) {
                $turno = 'Tarda';
            } else {
                $turno = 'Nit';
            }

            $clave = $dia . '_' . $turno;

            if (!isset($resultados[$clave])) {
                $resultados[$clave] = [
                    'cv' => $cv,
                    'dia' => $dia,
                    'turno' => $turno
                ];
            }
        }

        return array_values($resultados);
    }
}
