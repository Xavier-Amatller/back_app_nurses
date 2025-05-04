<?php

namespace App\Controller;

use App\Entity\Auxiliar;
use App\Entity\Dieta;
use App\Entity\Registro;
use App\Entity\TiposDieta;
use App\Entity\TiposTexturas;
use App\Form\DietaType;
use App\Repository\AuxiliarRepository;
use App\Repository\DietaRepository;
use App\Repository\HabitacionRepository;
use App\Repository\PacienteRepository;
use App\Repository\RegistroRepository;
use App\Repository\TiposDietaRepository;
use App\Repository\TiposTexturasRepository;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Util\Json;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\CssSelector\Node\HashNode;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
#[Route('/api/dieta')]
final class DietaController extends AbstractController
{
    // #[Route(name: 'app_dieta_index', methods: ['GET'])]
    // public function index(DietaRepository $dietaRepository): Response
    // {
    //     return $this->render('dieta/index.html.twig', [
    //         'dietas' => $dietaRepository->findAll(),
    //     ]);
    // }
    #[Route('/options', methods: ['GET'])]
    public function getDietSelectoptions(TiposDietaRepository $tiposDieta, TiposTexturasRepository $tiposTexturas): Response
    {
        $options = [];
        foreach ($tiposDieta->findAll() as $tipo) {
            $options['tipos_dieta'][] = [
                'id' => $tipo->getId(),
                'descripcion' => $tipo->getTDieDesc(),
            ];
        }
        foreach ($tiposTexturas->findAll() as $tipo) {
            $options['tipos_textura'][] = [
                'id' => $tipo->getId(),
                'descripcion' => $tipo->getTTextDesc(),
            ];
        }
        $response = [
            'status' => 200, // No need for http_response_code() here
            'data' => $options,
        ];

        return new JsonResponse($response);
    }

    #[Route('/{id}', name: 'app_dieta_show', methods: ['GET'])]
    public function getById(DietaRepository $dietaRepository, PacienteRepository $pacienteRepository, HabitacionRepository $habitacionRepository, RegistroRepository $registroRepository, int $id): Response
    {

        $roomId = $id;
        $room = $habitacionRepository->findOneBy(['hab_id' => $roomId]);
        $pacienteId = $room->getPaciente();
        $paciente = $pacienteRepository->find($pacienteId);
        $registros = $paciente->getRegistros();
        $dietaIds = [];

        foreach ($registros as $registro) {
            if ($registro->getDieta() == null) {
                continue;
            }
            $dietaIds[] = $registro->getDieta()->getId();
        }
        if(empty($dietaIds)){
            return new JsonResponse(
                [
                    'status' => 200,
                    'data' => [
                        'pac_id' => $pacienteId->getId(),
                    ],
                    'message' => 'No diet found for this patient, only patient ID returned.',
                ],
                Response::HTTP_OK
            );
        }


        $dietaIds = array_unique($dietaIds);
        $dieta = $dietaRepository->find($dietaIds[count($dietaIds) - 1]);

        return new JsonResponse(
            [
                'status' => 200,
                'data' => [
                    'pac_id' => $pacienteId->getId(),
                    'id' => $dieta->getId(),
                    'Die_Autonomo' => $dieta->isDieAutonomo(),
                    'Die_Protesi' => $dieta->isDieProtesi(),
                    'Die_TText' => [
                        'id' => $dieta->getDieTText()->getId(),
                        'descripcion' => $dieta->getDieTText()->getTTextDesc(),
                    ],
                    'Tipos_Dietas' => $dieta->getTiposDietas()->map(function (TiposDieta $tipo) {
                        return [
                            'id' => $tipo->getId(),
                            'descripcion' => $tipo->getTDieDesc(),
                        ];
                    })->toArray(),
                ],
            ]
        );

    }

    #[Route('/new', name: 'app_dieta_new', methods: ['POST'])]
    public function new(
        Request $request,
        EntityManagerInterface $entityManager,
        TiposDietaRepository $tiposDietaRepository,
        TiposTexturasRepository $tiposTexturasRepository,
        PacienteRepository $pacienteRepository,
        AuxiliarRepository $auxiliarRepository
    ): JsonResponse {
        $data = $request->toArray();

        $pacId = $data['pac_id'];
        $textureId = $data['textureId'];
        $dietTypes = $data['dietTypes'];
        $autonomy = $data['autonomy'];
        $prosthesis = $data['prosthesis'];
        $auxId = $data['aux_number'];

        // Create a new Registro
        $registro = new Registro();

        // Create a new Dieta
        $dieta = new Dieta();
        $dieta->setDieAutonomo($autonomy);
        $dieta->setDieProtesi($prosthesis);

        // Validate and set texture
        $texture = $tiposTexturasRepository->find($textureId);
        if (!$texture) {
            return new JsonResponse(
                ['status' => 'error', 'message' => 'Invalid texture ID'],
                Response::HTTP_BAD_REQUEST
            );
        }
        $dieta->setDieTText($texture);

        // Add diet types to Dieta
        foreach ($dietTypes as $dietTypeId) {
            $tipoDieta = $tiposDietaRepository->find($dietTypeId);
            if ($tipoDieta) {
                $dieta->addTiposDieta($tipoDieta);
            }
        }

        // Find patient and auxiliar
        $patient = $pacienteRepository->find($pacId);
        $auxiliar = $auxiliarRepository->find($auxId);

        // Associate Dieta with Registro
        $registro->setPaciente($patient);
        $registro->setDieta($dieta);
        $registro->setAuxiliar($auxiliar);
        $registro->setRegTimestamp(new \DateTime());

        // Persist the new Registro and Dieta
        $entityManager->persist($dieta);
        $entityManager->persist($registro);
        $entityManager->flush();

        return new JsonResponse(
            ['status' => 'success', 'message' => 'Registro and Dieta created successfully'],
            Response::HTTP_CREATED
        );
    }


    // #[Route('/{id}', name: 'app_dieta_show', methods: ['GET'])]
    // public function show(Dieta $dietum): Response
    // {
    //     return $this->render('dieta/show.html.twig', [
    //         'dietum' => $dietum,
    //     ]);
    // }

    #[Route('/{id}/edit', name: 'app_dieta_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Dieta $dietum, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(DietaType::class, $dietum);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_dieta_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('dieta/edit.html.twig', [
            'dietum' => $dietum,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_dieta_delete', methods: ['POST'])]
    public function delete(Request $request, Dieta $dietum, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $dietum->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($dietum);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_dieta_index', [], Response::HTTP_SEE_OTHER);
    }
}
