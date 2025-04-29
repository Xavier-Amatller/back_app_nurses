<?php

namespace App\Controller;

use App\Entity\ConstantesVitales;
use App\Entity\Diagnostico;
use App\Entity\Drenaje;
use App\Entity\Movilizacion;
use App\Entity\Registro;
use App\Repository\AuxiliarRepository;
use App\Repository\PacienteRepository;
use App\Repository\RegistroRepository;
use App\Repository\TiposDrenajesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/api/registro')]
#[IsGranted('ROLE_AUXILIAR')]
final class RegistroController extends AbstractController
{
    #[Route('/tipos-drenajes', name: 'tipos_drenajes_index', methods: ['GET'])]
    public function getTiposDrenajes(TiposDrenajesRepository $tiposDrenajesRepository): JsonResponse
    {
        $tiposDrenajes = $tiposDrenajesRepository->findAll();
        $data = array_map(function ($tipo) {
            return [
                'id' => $tipo->getId(),
                'tdre_desc' => $tipo->getTDreDesc(),
            ];
        }, $tiposDrenajes);

        return $this->json($data);
    }

    #[Route(name: 'registros_create', methods: ['POST'])]
    public function create(
        Request $request,
        AuxiliarRepository $auxiliarRepository,
        PacienteRepository $pacienteRepository,
        TiposDrenajesRepository $tiposDrenajesRepository, // Añadido
        EntityManagerInterface $entityManager
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);

        // Validar campos obligatorios
        if (
            !isset($data['aux_id']) ||
            !isset($data['pac_id']) ||
            !isset($data['reg_fecha']) ||
            !isset($data['reg_obs']) ||
            !isset($data['constantes_vitales']) ||
            !isset($data['constantes_vitales']['cv_ta_diastolica']) ||
            !isset($data['constantes_vitales']['cv_ta_sistolica']) ||
            !isset($data['constantes_vitales']['cv_pulso']) ||
            !isset($data['movilizaciones']) ||
            !isset($data['movilizaciones']['mov_sedestacion']) ||
            !isset($data['movilizaciones']['mov_ajuda_deambulacion'])
        ) {
            return $this->json(['error' => 'Faltan campos obligatorios'], 400);
        }

        // Validar que si se proporciona dre_debito, tdre_id sea obligatorio
        if (!empty($data['drenaje']['dre_debito']) && empty($data['drenaje']['tdre_id'])) {
            return $this->json(['error' => 'El tipo de drenaje es obligatorio si se proporciona un débito'], 400);
        }

        // Obtener el auxiliar
        $auxiliar = $auxiliarRepository->find($data['aux_id']);
        if (!$auxiliar) {
            return $this->json(['error' => 'Auxiliar no encontrado'], 404);
        }

        // Obtener el paciente
        $paciente = $pacienteRepository->find($data['pac_id']);
        if (!$paciente) {
            return $this->json(['error' => 'Paciente no encontrado'], 404);
        }

        // Crear las constantes vitales
        $constantesVitales = new ConstantesVitales();
        $constantesVitales->setCvTaSistolica($data['constantes_vitales']['cv_ta_sistolica']);
        $constantesVitales->setCvTaDiastolica($data['constantes_vitales']['cv_ta_diastolica']);
        $constantesVitales->setCvPulso($data['constantes_vitales']['cv_pulso']);
        $constantesVitales->setCvFrequenciaRespiratoria($data['constantes_vitales']['cv_frecuencia_respiratoria'] ?? null);
        $constantesVitales->setCvTemperatura($data['constantes_vitales']['cv_temperatura'] ?? null);
        $constantesVitales->setCvSaturacionOxigeno($data['constantes_vitales']['cv_saturacion_oxigeno'] ?? null);
        $constantesVitales->setCvTimestamp(new \DateTime());

        // Crear las movilizaciones
        $movilizacion = new Movilizacion();
        $movilizacion->setMovSedestacion($data['movilizaciones']['mov_sedestacion']);
        $movilizacion->setMovAjudaDeambulacion($data['movilizaciones']['mov_ajuda_deambulacion']);
        $movilizacion->setMovAjudaDescripcion($data['movilizaciones']['mov_ajuda_descripcion'] ?? null);
        $movilizacion->setMovCambios($data['movilizaciones']['mov_cambios'] ?? null);
        $movilizacion->setMovDecubitos($data['movilizaciones']['mov_decubitos'] ?? null);

        // Crear el diagnóstico (opcional)
        $diagnostico = null;
        if (!empty($data['diagnostico']['dia_diagnostico']) || !empty($data['diagnostico']['dia_motivo'])) {
            $diagnostico = new Diagnostico();
            $diagnostico->setDiaDiagnostico($data['diagnostico']['dia_diagnostico'] ?? null);
            $diagnostico->setDiaMotivo($data['diagnostico']['dia_motivo'] ?? null);
        }

        // Crear el drenaje (opcional)
        $drenaje = null;
        if (!empty($data['drenaje']['dre_debito'])) {
            $drenaje = new Drenaje();
            $drenaje->setDreDebito($data['drenaje']['dre_debito']);

            // Obtener el tipo de drenaje
            $tipoDrenaje = $tiposDrenajesRepository->find($data['drenaje']['tdre_id']);
            if (!$tipoDrenaje) {
                return $this->json(['error' => 'Tipo de drenaje no encontrado'], 404);
            }
            $drenaje->setTipoDrenaje($tipoDrenaje);
        }

        // Crear el registro
        $registro = new Registro();
        $registro->setAuxiliar($auxiliar);
        $registro->setPaciente($paciente);
        $registro->setRegTimestamp(new \DateTime($data['reg_fecha']));
        $registro->setConstantesVitales($constantesVitales);
        $registro->setMovilizacion($movilizacion);
        $registro->setDiagnostico($diagnostico);
        $registro->setDrenaje($drenaje);
        $registro->setDieta(null);

        // Guardar el registro usando el EntityManager
        $entityManager->persist($constantesVitales);
        $entityManager->persist($movilizacion);
        if ($diagnostico) {
            $entityManager->persist($diagnostico);
        }
        if ($drenaje) {
            $entityManager->persist($drenaje);
        }
        $entityManager->persist($registro);
        $entityManager->flush();

        // Construir la respuesta manualmente
        $responseData = [
            'message' => 'Registro creado con éxito',
            'registro' => [
                'id' => $registro->getId(),
                'aux_id' => $data['aux_id'],
                'pac_id' => $data['pac_id'],
                'reg_fecha' => $registro->getRegTimestamp()->format('c'),
            ],
        ];

        return $this->json($responseData, 201);
    }

    #[Route('/{id}', name: 'app_registro_show', methods: ['GET'])]
    public function show(Registro $registro): Response
    {
        return $this->json(['message' => 'Show Not implemented'], 201);
    }

    #[Route('/{id}/edit', name: 'app_registro_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Registro $registro, EntityManagerInterface $entityManager): Response
    {
        return $this->json(['message' => 'Edit Not implemented'], 201);
    }

    #[Route('/{id}', name: 'app_registro_delete', methods: ['POST'])]
    public function delete(Request $request, Registro $registro, EntityManagerInterface $entityManager): Response
    {
        return $this->json(['message' => 'Delete Not implemented'], 201);
    }
}
