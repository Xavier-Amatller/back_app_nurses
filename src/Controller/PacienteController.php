<?php

namespace App\Controller;

use App\Entity\Paciente;
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
}