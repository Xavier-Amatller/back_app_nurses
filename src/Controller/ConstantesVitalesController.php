<?php

namespace App\Controller;

use App\Entity\Registro;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/api/constantes-vitales')]
#[IsGranted('ROLE_AUXILIAR')]
class ConstantesVitalesController extends AbstractController
{
    private $registroRep;
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->registroRep = $entityManager->getRepository(Registro::class);
    }


    #[Route('/chart/{patientId}', name: 'app_constantes_vitales_chart', methods: ['GET'])]
    public function getChartData(int $patientId): JsonResponse
    {
        // Obtener los registros reales desde el repositorio
        $registros = $this->registroRep->getCVUltimosXdias($patientId);

        $datosPorClave = [];
        foreach ($registros as $item) {
            $fecha = $item['cv']->getCvTimestamp();
            $diaClave = $fecha->format('Y-m-d');

            $hora = (int) $fecha->format('H');
            if ($hora >= 7 && $hora < 13) {
                $turno = 'Matí';
            } elseif ($hora >= 13 && $hora < 18) {
                $turno = 'Tarda';
            } else {
                $turno = 'Nit';
            }

            $clave = $diaClave . '_' . $turno;
            $datosPorClave[$clave] = $item;
        }

        $turnos = ['Matí', 'Tarda', 'Nit'];

        $hoy = new \DateTimeImmutable('today');
        $diasSemana = ['Diumenge', 'Dilluns', 'Dimarts', 'Dimecres', 'Dijous', 'Divendres', 'Dissabte'];

        $resultados = [];

        for ($i = 6; $i >= 0; $i--) {
            $fecha = $hoy->modify("-$i days");
            $diaClave = $fecha->format('Y-m-d');
            $nombreDia = $diasSemana[(int)$fecha->format('w')];
            $fechaFormateada = $fecha->format('d/m/Y');

            foreach ($turnos as $turno) {
                $clave = $diaClave . '_' . $turno;

                if (isset($datosPorClave[$clave])) {
                    $cv = $datosPorClave[$clave]['cv'];

                    $resultados[] = [
                        'ta_sistolica' => $cv->getCvTaSistolica(),
                        'ta_diastolica' => $cv->getCvTaDiastolica(),
                        'frecuencia_respiratoria' => $cv->getCvFrequenciaRespiratoria(),
                        'pulso' => $cv->getCvPulso(),
                        'temperatura' => $cv->getCvTemperatura(),
                        'saturacion_oxigeno' => $cv->getCvSaturacionOxigeno(),
                        'talla' => $cv->getCvTalla(),
                        'diuresis' => $cv->getCvDiuresis(),
                        'deposiciones' => $cv->getCvDeposiciones(),
                        'stp' => $cv->getCvStp(),
                        'timestamp' => $cv->getCvTimestamp()->format('Y-m-d H:i:s'),
                        'turno' => $turno,
                        'label' => "$nombreDia $turno ($fechaFormateada)"
                    ];
                } else {
                    // Relleno vacío
                    $resultados[] = [
                        'ta_sistolica' => null,
                        'ta_diastolica' => null,
                        'frecuencia_respiratoria' => null,
                        'pulso' => null,
                        'temperatura' => null,
                        'saturacion_oxigeno' => null,
                        'talla' => null,
                        'diuresis' => null,
                        'deposiciones' => null,
                        'stp' => null,
                        'timestamp' => $fecha->format('Y-m-d') . ' 00:00:00',
                        'turno' => $turno,
                        'label' => "$nombreDia $turno ($fechaFormateada)"
                    ];
                }


                if (count($resultados) >= 21) break 2;
            }
        }

        return $this->json($resultados);
    }
    /* #[Route(name: 'app_constantes_vitales_index', methods: ['GET'])]

    #[Route('/new', name: 'app_constantes_vitales_new', methods: ['GET', 'POST'])]

    #[Route('/{id}', name: 'app_constantes_vitales_show', methods: ['GET'])]

    #[Route('/{id}/edit', name: 'app_constantes_vitales_edit', methods: ['GET', 'POST'])]
   
    #[Route('/{id}', name: 'app_constantes_vitales_delete', methods: ['POST'])] */
}
