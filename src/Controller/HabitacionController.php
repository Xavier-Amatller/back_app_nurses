<?php

namespace App\Controller;

use App\Entity\Habitacion;
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
            $patient = $room->getPaciente();
            return [
                'id' => $room->getId(),
                'hab_id' => $room->getHabId(),
                'hab_obs' => $room->getHabObs(),
                'paciente' => $patient ? [
                    'pac_numhistorial' => $patient->getPacNumhistorial(),
                    'pac_nombre' => $patient->getPacNombre(),
                    'pac_apellidos' => $patient->getPacApellidos(),
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
}
