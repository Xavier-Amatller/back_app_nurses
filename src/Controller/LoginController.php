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
use Symfony\Component\Security\Http\Attribute\IsGranted;


#[Route('/api')]
final class LoginController extends AbstractController
{
    private $auxiliarRep;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->auxiliarRep = $entityManager->getRepository(Auxiliar::class);
    }

    #[Route('/login', name: 'api_login', methods: ['POST'])]
    public function login(Request $request, JWTTokenManagerInterface $jwtManager, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $numTrabajador = $data['aux_num_trabajador'] ?? '';
        $password = $data['aux_password'] ?? '';

        if (!$numTrabajador || !$password) {
            return new JsonResponse(['error' => 'Credenciales incompletas'], 400);
        }

        $auxiliar = $this->auxiliarRep->findOneBy(['aux_num_trabajador' => $numTrabajador]);

        if (!$auxiliar) {
            return new JsonResponse(['error' => 'Auxiliar no encontrado'], 404);
        }

        // Verificar si la contraseña necesita hashearse (primera vez)
        if (!$passwordHasher->isPasswordValid($auxiliar, $password)) {
            // Si la contraseña no es válida pero coincide en texto plano (primera vez)
            if ($auxiliar->getAuxPassword() === $password) {
                $auxiliar->setAuxPassword($passwordHasher->hashPassword($auxiliar, $password));
                $entityManager->flush();
            } else {
                return new JsonResponse(['error' => 'Credenciales inválidas'], 401);
            }
        }

        $token = $jwtManager->create($auxiliar);

        return new JsonResponse([
            'token' => $token,
            'userId' => $auxiliar->getAuxNumTrabajador(),
            'roles' => $auxiliar->getRoles()
        ]);
    }

}