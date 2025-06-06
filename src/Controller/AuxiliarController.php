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


#[Route('/api/auxiliares')]
#[IsGranted('ROLE_ADMIN')]
final class AuxiliarController extends AbstractController
{
    private $auxiliarRep;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->auxiliarRep = $entityManager->getRepository(Auxiliar::class);
    }

    #[Route('', name: 'api_create_auxiliar', methods: ['POST'])]
    public function createAuxiliar(Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager): JsonResponse
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN'); // Solo administradores pueden crear auxiliares

        $data = json_decode($request->getContent(), true);
        $auxNumTrabajador = $data['aux_num_trabajador'] ?? null;
        $auxNombre = $data['aux_nombre'] ?? null;
        $auxApellidos = $data['aux_apellidos'] ?? null;
        $auxPassword = $data['aux_password'] ?? null;
        $roles = $data['roles'] ?? ['ROLE_AUXILIAR'];

        if (!$auxNumTrabajador || !$auxNombre || !$auxApellidos || !$auxPassword) {
            return new JsonResponse(['error' => 'Datos incompletos'], 400);
        }

        // Verificar si el número de trabajador ya existe
        $existingAuxiliar = $this->auxiliarRep->findOneBy(['aux_num_trabajador' => $auxNumTrabajador]);
        if ($existingAuxiliar) {
            return new JsonResponse(['error' => 'El número de trabajador ya existe'], 409);
        }

        $auxiliar = new Auxiliar();
        $auxiliar->setAuxNumTrabajador($auxNumTrabajador);
        $auxiliar->setAuxNombre($auxNombre);
        $auxiliar->setAuxApellidos($auxApellidos);
        $auxiliar->setAuxPassword($passwordHasher->hashPassword($auxiliar, $auxPassword));
        $auxiliar->setRoles($roles);

        $entityManager->persist($auxiliar);
        $entityManager->flush();

        return new JsonResponse(['message' => 'Auxiliar creado con éxito', 'id' => $auxiliar->getId()], 201);
    }

    #[Route('', name: 'api_update_auxiliar', methods: ['PUT'])]
    public function updateAuxiliar(Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager): JsonResponse
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN'); // Solo administradores pueden actualizar auxiliares

        $data = json_decode($request->getContent(), true);
        $auxNumTrabajador = $data['aux_num_trabajador'] ?? null;
        $auxNombre = $data['aux_nombre'] ?? null;
        $auxApellidos = $data['aux_apellidos'] ?? null;
        $auxPassword = $data['aux_password'] ?? null;
        $roles = $data['roles'] ?? ['ROLE_AUXILIAR'];

        if (!$auxNumTrabajador || !$auxNombre || !$auxApellidos) {
            return new JsonResponse(['error' => 'Datos incompletos'], 400);
        }

        // Verificar si el número de trabajador ya existe
        $existingAuxiliar = $this->auxiliarRep->findOneBy(['aux_num_trabajador' => $auxNumTrabajador]);
        if (!$existingAuxiliar) {
            return new JsonResponse(['error' => 'Auxiliar no encontrado'], 404);
        }

        $existingAuxiliar->setAuxNombre($auxNombre);
        $existingAuxiliar->setAuxApellidos($auxApellidos);

        // Solo actualizar la contraseña si se proporciona una nueva
        if (!empty($auxPassword)) {
            $existingAuxiliar->setAuxPassword($passwordHasher->hashPassword($existingAuxiliar, $auxPassword));
        }

        $existingAuxiliar->setRoles($roles);

        $entityManager->flush();

        $auxiliar = $existingAuxiliar;

        return new JsonResponse(['message' => 'Auxiliar actualizado con éxito', 'id' => $auxiliar->getId()], 200);
    }

    #[Route('/{auxNumTrabajador}/show', name: 'api_find_auxiliar', methods: ['GET'])]
    public function findAuxiliar(string $auxNumTrabajador): JsonResponse
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN'); // Solo administradores pueden crear auxiliares

        if (!$auxNumTrabajador) {
            return new JsonResponse(['error' => 'Datos incompletos'], 400);
        }

        // Verificar si el número de trabajador ya existe
        $existingAuxiliar = $this->auxiliarRep->findOneBy(['aux_num_trabajador' => $auxNumTrabajador]);
        if ($existingAuxiliar) {
            // Devuelve toda la información del auxiliar como array
            $auxiliarData = [
            'id' => $existingAuxiliar->getId(),
            'aux_num_trabajador' => $existingAuxiliar->getAuxNumTrabajador(),
            'aux_nombre' => $existingAuxiliar->getAuxNombre(),
            'aux_apellidos' => $existingAuxiliar->getAuxApellidos(),
            'roles' => $existingAuxiliar->getRoles(),
            // No devuelvas la contraseña por seguridad
            ];
        }
        if (!$existingAuxiliar) {
            return new JsonResponse(['error' => 'Auxiliar no encontrado'], 404);
        }

        return new JsonResponse($auxiliarData, 200);
    }
}