<?php

namespace App\Controller;

use App\Entity\Habitacion;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HabitacionController extends AbstractController
{

    private $habitacionRep;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->habitacionRep = $entityManager->getRepository(Habitacion::class);
    }

    #[Route('/test/rooms', name: 'api_habitaciones', methods: ['GET'])]
    public function getHabitaciones(Request $request): JsonResponse
    {
        $page = $request->query->getInt('page', 1);
        $limit = $request->query->getInt('limit', 4);

        $rooms = $this->habitacionRep->findPaginated($page, $limit);

        $data = array_map(function ($room) {

            if($room->getPaciente() === null) {
                return [
                    'hab_id' => $room->getHabId(),
                    'hab_obs' => $room->getHabObs(),
                    'paciente' => null,
                ];
            }

            $patient = $room->getPaciente();
            $fechaNacimiento = $patient->getPacFechaNacimiento();
            $edad = $this->calcularEdad($fechaNacimiento);
            
            return [
                'hab_id' => $room->getHabId(),
                'hab_obs' => $room->getHabObs(),
                'paciente' => $patient ? [
                    'pac_id' => $patient->getId(),
                    'pac_nombre' => $patient->getPacNombre(),
                    'pac_apellidos' => $patient->getPacApellidos(),
                    'pac_edad' => $edad,
                    'pac_fecha_ingreso' => $patient->getPacFechaIngreso()->format('d-m-Y'),
                ] : null,
            ];
        },  $rooms['rooms']);

        return new JsonResponse([
            'rooms' => $data,
            'totalItems' => $rooms['totalRooms'],
            'page' => $page,
            'limit' => $limit,
        ]);
    }

    private function calcularEdad(\DateTimeInterface $fechaNacimiento): int
    {
        $fechaActual = new DateTime();
        $diferencia = $fechaActual->diff($fechaNacimiento);
        return $diferencia->y;
    }
}
