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

        $data = $this->habitacionRep->findPaginated($page, $limit);

        $rooms = array_map(function ($room) {

            $patient = $room->getPaciente();

            if ($patient) {
                $fechaNacimiento = $patient->getPacFechaNacimiento();
                $edad = $this->calcularEdad($fechaNacimiento);
            }

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
        },  $data['rooms']);

        return new JsonResponse([
            'rooms' => $rooms,
            'totalItems' => $data['totalRooms'],
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


    #[Route('/test/rooms/show/', name: 'api_habitaciones_id', methods: ['GET'])]
    public function show(Request $request): JsonResponse
    {
        $room_id = $request->query->getInt('id', 0);

        $data = $this->habitacionRep->showRoom($room_id);

        $room = array_map(function ($room) {

            $patient = $room->getPaciente();
            $fechaNacimiento = $patient->getPacFechaNacimiento();
            $edad = $this->calcularEdad($fechaNacimiento);

            return [
                'hab_obs' => $room->getHabObs(),
                'paciente' => $patient ? [
                    'pac_id' => $patient->getId(),
                    'pac_nombre' => $patient->getPacNombre(),
                    'pac_apellidos' => $patient->getPacApellidos(),
                    'pac_edad' => $edad,
                    'pac_fecha_ingreso' => $patient->getPacFechaIngreso()->format('d-m-Y'),
                ] : null,
            ];
        },  $data['room']);


        return new JsonResponse($room);
    }
}
