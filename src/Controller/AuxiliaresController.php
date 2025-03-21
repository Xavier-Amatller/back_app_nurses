<?php

namespace App\Controller;

use App\Entity\Auxiliar;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class AuxiliaresController extends AbstractController{
    #[Route('/auxiliares', name: 'app_auxiliares')]
    public function index(): Response
    {
        return $this->render('auxiliares/index.html.twig', [
            'controller_name' => 'AuxiliaresController',
        ]);
    }

    #[Route('/auxiliares/listar', name: 'app_auxiliares_crear')]
    public function listar(EntityManagerInterface $entityManager): Response
    {

        $auxiliarrepository = $entityManager->getRepository(Auxiliar::class);

        $auxiliares = $auxiliarrepository->listAuxiliares();

        return new JsonResponse([
            'auxiliares' => $auxiliares
        ]);
    }

}
