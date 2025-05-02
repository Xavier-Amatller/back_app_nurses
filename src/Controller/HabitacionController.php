<?php

namespace App\Controller;

use App\Entity\Habitacion;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/api/rooms')]
#[IsGranted('ROLE_AUXILIAR')]
final class HabitacionController extends AbstractController
{

    private $habitacionRep;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->habitacionRep = $entityManager->getRepository(Habitacion::class);
    }

    // #[Route('', name: 'api_habitaciones_index', methods: ['GET'])]
    // public function index(Request $request): JsonResponse

    #[Route('', name: 'api_habitaciones', methods: ['GET'])]

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

    // private function calcAge(\DateTimeInterface $fechaNacimiento): int
    private function calcularEdad(\DateTimeInterface $fechaNacimiento): int
    {
        $fechaActual = new DateTime();
        $diferencia = $fechaActual->diff($fechaNacimiento);
        return $diferencia->y;
    }


    // #[Route('{id}', name: 'api_habitaciones_show', methods: ['GET'])]
    // public function show(Request $request): JsonResponse

    #[Route('/show', name: 'api_habitaciones_id', methods: ['GET'])]
    public function show(Request $request): JsonResponse
    {
        $room_id = $request->query->getInt('id', 0);

        $data = $this->habitacionRep->findBy(['hab_id' => $room_id]);

        $room = array_map(function ($room) {

            $patient = $room->getPaciente();
            $fechaNacimiento = $patient->getPacFechaNacimiento();
            $edad = $this->calcularEdad($fechaNacimiento);

            return [
                'hab_obs' => $room->getHabObs(),
                'paciente' => $patient ? [
                    'pac_id' => $patient->getId(),
                    'pac_num_historial' => $patient->getPacNumHistorial(),
                    'pac_nombre' => $patient->getPacNombre(),
                    'pac_apellidos' => $patient->getPacApellidos(),
                    'pac_edad' => $edad,
                    'pac_fecha_nacimiento' => $patient->getPacFechaNacimiento()->format('d-m-Y'),
                    'pac_lengua_materna' => $patient->getPacLenguaMaterna(),
                    'pac_direccion_completa' => $patient->getPacDireccionCompleta(),
                    'pac_antecedentes' => $patient->getPacAntecedentes(),
                    'pac_alergias' => $patient->getPacAlergias(),
                    'pac_nombre_cuidador' => $patient->getPacNombreCuidador(),
                    'pac_telefono_cuidador' => $patient->getPacTelefonoCuidador(),
                    'pac_fecha_ingreso' => $patient->getPacFechaIngreso()->format('d-m-Y'),
                ] : null,
            ];
        },  $data);


        return new JsonResponse($room);
    }
}
