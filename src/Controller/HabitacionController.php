<?php

namespace App\Controller;

use App\Entity\Habitacion;
use App\Entity\Paciente;
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
    private $pacienteRep;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->habitacionRep = $entityManager->getRepository(Habitacion::class);
        $this->pacienteRep = $entityManager->getRepository(Paciente::class);
    }

    #[Route('', name: 'api_habitaciones_index', methods: ['GET'])]
    public function index(Request $request): JsonResponse
    {
        $page = $request->query->getInt('page', 1);
        $limit = $request->query->getInt('limit', 4);

        $data = $this->habitacionRep->findPaginated($page, $limit);

        $rooms = array_map(function ($room) {
            $patient = $room->getPaciente();

            if ($patient) {
                $fechaNacimiento = $patient->getPacFechaNacimiento();
                $edad = $this->calcAge($fechaNacimiento);
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
        }, $data['rooms']);

        return new JsonResponse([
            'rooms' => $rooms,
            'totalItems' => $data['totalRooms'],
            'page' => $page,
            'limit' => $limit,
        ]);
    }

    private function calcAge(\DateTimeInterface $fechaNacimiento): int
    {
        $fechaActual = new DateTime();
        $diferencia = $fechaActual->diff($fechaNacimiento);
        return $diferencia->y;
    }

    #[Route('/show', name: 'api_habitaciones_show', methods: ['GET'])]
    public function show(Request $request): JsonResponse
    {
        $hab_id = $request->query->getInt('hab_id', 0);

        $data = $this->habitacionRep->findBy(['hab_id' => $hab_id]);

        $room = array_map(function ($room) {
            $patient = $room->getPaciente();
            if (!$patient) {
                return [
                    'hab_id' => $room->getHabId(),
                    'hab_obs' => $room->getHabObs(),
                    'paciente' => null
                ];
            }

            $fechaNacimiento = $patient->getPacFechaNacimiento();
            $edad = $this->calcAge($fechaNacimiento);

            return [
                'hab_id' => $room->getHabId(),
                'hab_obs' => $room->getHabObs(),
                'paciente' => $patient
                    ? [
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
                    ]
                    : null,
            ];
        }, $data);

        return new JsonResponse($room);
    }

    #[Route('/{hab_id}/unsubscribe', name: 'api_habitaciones_unsubscribe', methods: ['PUT'])]
    public function unsubscribe(string $hab_id, EntityManagerInterface $entityManager): JsonResponse
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $room = $this->habitacionRep->findOneBy(['hab_id' => $hab_id]);

        if (!$room) {
            return new JsonResponse(['error' => 'Habitación no encontrada'], 404);
        }

        if (!$room->getPaciente()) {
            return new JsonResponse([
                'message' => 'Habitación ya vacía',
                'data' => [
                    'hab_id' => $room->getHabId(),
                    'hab_obs' => $room->getHabObs(),
                    'paciente' => $room->getPaciente()
                ]
            ]);
        }

        $room->setHabObs('');
        $room->setPaciente(null);

        $entityManager->persist($room);
        $entityManager->flush();

        return new JsonResponse([
            'message' => 'Paciente desasignado correctamente',
            'data' => [
                'hab_id' => $room->getHabId(),
                'hab_obs' => $room->getHabObs(),
                'paciente' => $room->getPaciente()
            ]
        ]);
    }

    #[Route('/{hab_id}/assign', name: 'api_habitaciones_assign', methods: ['POST'])]
    public function assign(string $hab_id, Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $room = $this->habitacionRep->findOneBy(['hab_id' => $hab_id]);
        if (!$room) {
            return new JsonResponse(['error' => 'Habitación no encontrada'], 404);
        }

        if ($room->getPaciente()) {
            return new JsonResponse(['error' => 'La habitación ya tiene un paciente asignado'], 400);
        }

        $data = json_decode($request->getContent(), true);
        $patientId = $data['patientId'] ?? null;

        if (!$patientId) {
            return new JsonResponse(['error' => 'ID del paciente no proporcionado'], 400);
        }

        $patient = $this->pacienteRep->find($patientId);
        if (!$patient) {
            return new JsonResponse(['error' => 'Paciente no encontrado'], 404);
        }

        $room->setPaciente($patient);
        $room->setHabObs('Ocupada');

        $entityManager->persist($room);
        $entityManager->flush();

        return new JsonResponse([
            'message' => 'Paciente asignado correctamente',
            'data' => [
                'hab_id' => $room->getHabId(),
                'hab_obs' => $room->getHabObs(),
                'paciente' => [
                    'pac_id' => $patient->getId(),
                    'pac_nombre' => $patient->getPacNombre(),
                    'pac_apellidos' => $patient->getPacApellidos(),
                    'pac_edad' => $this->calcAge($patient->getPacFechaNacimiento()),
                    'pac_fecha_ingreso' => $patient->getPacFechaIngreso()->format('d-m-Y'),
                ]
            ]
        ]);
    }
}