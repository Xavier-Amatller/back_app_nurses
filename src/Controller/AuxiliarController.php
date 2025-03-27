<?php

namespace App\Controller;

use App\Entity\Auxiliar;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;


final class AuxiliarController extends AbstractController  {
    private $auxiliarRep;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->auxiliarRep = $entityManager->getRepository(Auxiliar::class);
    }

    #[Route('/auxiliares', name: 'app_auxiliares')]
    public function index(): Response
    {
        return $this->render('auxiliares/index.html.twig', [
            'controller_name' => 'AuxiliaresController',
        ]);
    }

    #[Route('/auxiliares/listar', name: 'app_auxiliares_crear')]
    public function listar(): Response
    {

        $auxiliares = $this->auxiliarRep->listAuxiliares();

        return new JsonResponse($auxiliares, 200, [], true);
    }


    /* Test Tokens */

    #[Route('/api/test', name: 'api_test', methods: ['GET'])]
    public function test(): JsonResponse
    {
        return new JsonResponse(['message' => 'It works']);
    }

    
    #[Route('/api/login', name: 'api_login', methods: ['POST'])]
    public function login(Request $request, JWTTokenManagerInterface $jwtManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $numTrabajador = $data['aux_num_trabajador'] ?? '';
        $password = $data['aux_password'] ?? '';

        $auxiliar = $this->auxiliarRep->findOneBy(['aux_num_trabajador' => $numTrabajador]);

        if (!$auxiliar){
            return new JsonResponse();
        }

        $token = $jwtManager->create($auxiliar);
        return new JsonResponse(['token' => $token]);
    }

}
