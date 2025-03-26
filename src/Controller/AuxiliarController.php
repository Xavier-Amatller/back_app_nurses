<?php

namespace App\Controller;

use App\Entity\Auxiliar;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
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

}
