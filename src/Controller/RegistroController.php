<?php

namespace App\Controller;

use App\Entity\ConstantesVitales;
use App\Entity\Diagnostico;
use App\Entity\Dieta;
use App\Entity\Drenaje;
use App\Entity\Movilizacion;
use App\Entity\Registro;
use App\Entity\TiposDieta;
use App\Repository\AuxiliarRepository;
use App\Repository\PacienteRepository;
use App\Repository\TiposDietaRepository;
use App\Repository\TiposDrenajesRepository;
use App\Repository\TiposTexturasRepository;
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
    private $RegistroRep;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->RegistroRep = $entityManager->getRepository(Registro::class);
    }

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

    #[Route('/tipos-texturas', name: 'tipos_texturas_index', methods: ['GET'])]
    public function getTiposTexturas(TiposTexturasRepository $tiposTexturasRepository): JsonResponse
    {
        $tiposTexturas = $tiposTexturasRepository->findAll();
        $data = array_map(function ($tipo) {
            return [
                'id' => $tipo->getId(),
                'ttex_desc' => $tipo->getTTextDesc(),
            ];
        }, $tiposTexturas);

        return $this->json($data);
    }

    #[Route('/tipos-dietas', name: 'tipos_dietas_index', methods: ['GET'])]
    public function getTiposDietas(TiposDietaRepository $tiposDietaRepository): JsonResponse
    {
        $tiposDietas = $tiposDietaRepository->findAll();
        $data = array_map(function ($tipo) {
            return [
                'id' => $tipo->getId(),
                'tdie_desc' => $tipo->getTdieDesc(),
            ];
        }, $tiposDietas);

        return $this->json($data);
    }

    #[Route(name: 'registros_create', methods: ['POST'])]
    public function create(
        Request $request,
        AuxiliarRepository $auxiliarRepository,
        PacienteRepository $pacienteRepository,
        TiposDrenajesRepository $tiposDrenajesRepository,
        EntityManagerInterface $entityManager,
        TiposTexturasRepository $tiposTexturasRepository,
        TiposDietaRepository $tiposDietaRepository,
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

        // Validar dietas
        if (!empty($data['dieta']['die_ttext'])) {
            if (
                empty($data['dieta']['tipos_dietas']) ||
                !isset($data['dieta']['die_autonomo']) ||
                !isset($data['dieta']['die_protesi'])
            ) {
                return $this->json(['error' => 'Todos los campos de dieta son obligatorios si se proporciona una textura'], 400);
            }
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

        // Crear dieta (opcional)
        $dieta = null;
        if (!empty($data['dieta']['die_ttext'])) {
            $dieta = new Dieta();

            // Asignar textura
            $tipoTextura = $tiposTexturasRepository->find($data['dieta']['die_ttext']);
            if (!$tipoTextura) {
                return $this->json(['error' => 'Tipo de textura no encontrado'], 404);
            }
            $dieta->setDieTText($tipoTextura);

            // Asignar tipos de dieta
            foreach ($data['dieta']['tipos_dietas'] as $tipoDietaId) {
                $tipoDieta = $tiposDietaRepository->find($tipoDietaId);
                if (!$tipoDieta) {
                    return $this->json(['error' => 'Tipo de dieta no encontrado: ' . $tipoDietaId], 404);
                }
                $dieta->addTiposDieta($tipoDieta);
            }

            // Asignar campos booleanos
            $dieta->setDieAutonomo($data['dieta']['die_autonomo']);
            $dieta->setDieProtesi($data['dieta']['die_protesi']);
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
        $registro->setDieta($dieta);

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


    #[Route('/last', name: 'get_last_record', methods: ['GET'])]
    public function getLastRecordByPatient(Request $request): JsonResponse
    {
        $pac_id = $request->query->getInt('id');

        $data = $this->RegistroRep->lastRegistro($pac_id);

        $lastRegistro = array_map(function ($reg) {

            $cv = $reg->getConstantesVitales();
            $die = $reg->getDieta();
            $mov = $reg->getMovilizacion();
            $dia = $reg->getDiagnostico();
            $dre = $reg->getDrenaje();

            return [
                'reg_timestamp' => $reg->getRegTimestamp()->format('d/m/Y H:i:s'),
                'cv' => $cv ? [
                    'cv_ta_sistolica' => $cv->getCvTaSistolica(),
                    'cv_ta_diastolica' => $cv->getCvTaDiastolica(),
                    'cv_frequencia_respiratoria' => $cv->getCvFrequenciaRespiratoria(),
                    'cv_pulso' => $cv->getCvPulso(),
                    'cv_temperatura' => $cv->getCvTemperatura(),
                    'cv_saturacion_oxigeno' => $cv->getCvSaturacionOxigeno(),
                    'cv_talla' => $cv->getCvTalla(),
                    'cv_diuresis' => $cv->getCvDiuresis(),
                    'cv_deposiciones' => $cv->getCvDeposiciones(),
                    'cv_stp' => $cv->getCvStp(),
                ] : null,
                'die' => $die ? [
                    'die_ttext' => $die->getDieTText()->getTTextDesc(),
                    'die_autonomo' => $die->isDieAutonomo(),
                    'die_protesi' => $die->isDieProtesi(),
                    'die_tdieta' => $die->getTiposDietas()->map(function (TiposDieta $tipo) {
                        return [
                            'id' => $tipo->getId(),
                            'descripcion' => $tipo->getTDieDesc(),
                        ];
                    })->toArray(),
                ] : null,
                'mov' => $mov ? [
                    'mov_sedestacion' => $mov->isMovSedestacion(),
                    'mov_ajuda_deambulacion' => $mov->isMovAjudaDeambulacion(),
                    'mov_ajuda_descripcion' => $mov->getMovAjudaDescripcion(),
                    'mov_cambios' => $mov->getMovCambios(),
                    'mov_decubitos' => $mov->getMovDecubitos(),
                ] : null,
                'dia' => $dia ? [
                    'dia_diagnostico' => $dia->getDiaDiagnostico(),
                    'dia_motivo' => $dia->getDiaMotivo(),
                ] : null,
                'dre' => $dre ? [
                    'dre_debito' => $dre->getDreDebito(),
                    'tdre_desc' => $dre->getTipoDrenaje()->getTDreDesc(),
                ] : null,

            ];
        },  $data);

        return $this->json($lastRegistro);
    }
}
