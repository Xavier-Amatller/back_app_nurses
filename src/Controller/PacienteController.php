<?php

namespace App\Controller;

use App\Entity\Paciente;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/api/pacientes')]
#[IsGranted('ROLE_ADMIN')]
class PacienteController extends AbstractController
{
    private $patientRep;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->patientRep = $entityManager->getRepository(Paciente::class);
    }

    #[Route('', name: 'api_create_patient', methods: ['POST'])]
    public function createPatient(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN'); // Solo administradores pueden crear pacientes

        $data = json_decode($request->getContent(), true);
        $pacNumHistorial = $data['pac_num_historial'] ?? null;
        $pacNombre = $data['pac_nombre'] ?? null;
        $pacApellidos = $data['pac_apellidos'] ?? null;
        $pacFechaNacimiento = $data['pac_fecha_nacimiento'] ? new \DateTime($data['pac_fecha_nacimiento']) : null;
        $pacDireccionCompleta = $data['pac_direccion_completa'] ?? null;
        $pacLenguaMaterna = $data['pac_lengua_materna'] ?? null;
        $pacAntecedentes = $data['pac_antecedentes'] ?? null;
        $pacAlergias = $data['pac_alergias'] ?? null;
        $pacNombreCuidador = $data['pac_nombre_cuidador'] ?? null;
        $pacTelefonoCuidador = $data['pac_telefono_cuidador'] ?? null;
        $pacFechaIngreso = $data['pac_fecha_ingreso'] ? new \DateTime($data['pac_fecha_ingreso']) : null;
        $pacTimestamp = $data['pac_timestamp'] ? new \DateTime($data['pac_timestamp']) : new \DateTime();

        if (
            !$pacNumHistorial ||
            !$pacNombre ||
            !$pacApellidos ||
            !$pacFechaNacimiento ||
            !$pacDireccionCompleta ||
            !$pacLenguaMaterna ||
            !$pacAntecedentes ||
            !$pacAlergias ||
            !$pacNombreCuidador ||
            !$pacTelefonoCuidador ||
            !$pacFechaIngreso
        ) {
            return new JsonResponse(['error' => 'Datos incompletos'], 400);
        }

        // Verificar si el número de historial ya existe
        $existingPatient = $this->patientRep->findOneBy(['pac_num_historial' => $pacNumHistorial]);
        if ($existingPatient) {
            return new JsonResponse(['error' => 'El número de historial ya existe'], 409);
        }

        $patient = new Paciente();
        $patient->setPacNumhistorial($pacNumHistorial);
        $patient->setPacNombre($pacNombre);
        $patient->setPacApellidos($pacApellidos);
        $patient->setPacFechaNacimiento($pacFechaNacimiento);
        $patient->setPacDireccionCompleta($pacDireccionCompleta);
        $patient->setPacLenguaMaterna($pacLenguaMaterna);
        $patient->setPacAntecedentes($pacAntecedentes);
        $patient->setPacAlergias($pacAlergias);
        $patient->setPacNombreCuidador($pacNombreCuidador);
        $patient->setPacTelefonoCuidador($pacTelefonoCuidador);
        $patient->setPacFechaIngreso($pacFechaIngreso);
        $patient->setPacTimestamp($pacTimestamp);

        $entityManager->persist($patient);
        $entityManager->flush();

        return new JsonResponse(['message' => 'Paciente creado con éxito', 'id' => $patient->getId()], 201);
    }

    #[Route('/{pac_num_historial}/show', name: 'api_patient_show', methods: ['GET'])]
    public function show(string $pac_num_historial, Request $request): JsonResponse
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $patient = $this->patientRep->findOneBy(['pac_num_historial' => $pac_num_historial]);

        if (!$patient) {
            return new JsonResponse(['error' => 'Paciente no encontrado', $pac_num_historial]);
        }

        return new JsonResponse(["paciente" => [
            'pac_id' => $patient->getId(),
            'pac_num_historial' => $patient->getPacNumHistorial(),
            'pac_nombre' => $patient->getPacNombre(),
            'pac_apellidos' => $patient->getPacApellidos(),
            'pac_edad' => $patient->getPacFechaNacimiento(),
            'pac_fecha_nacimiento' => $patient->getPacFechaNacimiento()->format('d-m-Y'),
            'pac_lengua_materna' => $patient->getPacLenguaMaterna(),
            'pac_direccion_completa' => $patient->getPacDireccionCompleta(),
            'pac_antecedentes' => $patient->getPacAntecedentes(),
            'pac_alergias' => $patient->getPacAlergias(),
            'pac_nombre_cuidador' => $patient->getPacNombreCuidador(),
            'pac_telefono_cuidador' => $patient->getPacTelefonoCuidador(),
            'pac_fecha_ingreso' => $patient->getPacFechaIngreso()->format('d-m-Y'),
        ]]);
    }

    #[Route('/{pac_num_historial}', name: 'api_update_patient', methods: ['PUT'])]
    public function updatePatient(string $pac_num_historial, Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $patient = $this->patientRep->findOneBy(['pac_num_historial' => $pac_num_historial]);
        if (!$patient) {
            return new JsonResponse(['error' => 'Paciente no encontrado']);
        }

        $data = json_decode($request->getContent(), true);

        // Actualizar solo los campos proporcionados
        if (isset($data['pac_num_historial'])) {
            $existingPatient = $this->patientRep->findOneBy(['pac_num_historial' => $data['pac_num_historial']]);
            if ($existingPatient && $existingPatient->getId() !== $patient->getId()) {
                return new JsonResponse(['error' => 'El número de historial ya existe'], 409);
            }
            $patient->setPacNumhistorial($data['pac_num_historial']);
        }
        if (isset($data['pac_nombre'])) {
            $patient->setPacNombre($data['pac_nombre']);
        }
        if (isset($data['pac_apellidos'])) {
            $patient->setPacApellidos($data['pac_apellidos']);
        }
        if (isset($data['pac_fecha_nacimiento'])) {
            $patient->setPacFechaNacimiento(new \DateTime($data['pac_fecha_nacimiento']));
        }
        if (isset($data['pac_direccion_completa'])) {
            $patient->setPacDireccionCompleta($data['pac_direccion_completa']);
        }
        if (isset($data['pac_lengua_materna'])) {
            $patient->setPacLenguaMaterna($data['pac_lengua_materna']);
        }
        if (isset($data['pac_antecedentes'])) {
            $patient->setPacAntecedentes($data['pac_antecedentes']);
        }
        if (isset($data['pac_alergias'])) {
            $patient->setPacAlergias($data['pac_alergias']);
        }
        if (isset($data['pac_nombre_cuidador'])) {
            $patient->setPacNombreCuidador($data['pac_nombre_cuidador']);
        }
        if (isset($data['pac_telefono_cuidador'])) {
            $patient->setPacTelefonoCuidador($data['pac_telefono_cuidador']);
        }
        if (isset($data['pac_fecha_ingreso'])) {
            $patient->setPacFechaIngreso(new \DateTime($data['pac_fecha_ingreso']));
        }
        if (isset($data['pac_timestamp'])) {
            $patient->setPacTimestamp(new \DateTime($data['pac_timestamp']));
        }

        $entityManager->flush();

        return new JsonResponse(['message' => 'Paciente actualizado con éxito']);
    }
}
