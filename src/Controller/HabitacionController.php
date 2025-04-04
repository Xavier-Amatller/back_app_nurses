<?php

namespace App\Controller;

use App\Entity\Habitacion;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HabitacionController extends AbstractController
{

    private $habitacionRep;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->habitacionRep = $entityManager->getRepository(Habitacion::class);
    }


    #[Route('/test/habitaciones', name: 'api_habitaciones', methods: ['GET'])]
    public function getHabitaciones(Request $request): JsonResponse
    {
        $page = $request->query->getInt('page', 1);
        $limit = $request->query->getInt('limit', 4);

        $result = $this->habitacionRep->findPaginated($page, $limit);

        dd($result["data"]);

        return new JsonResponse([
            'data' => "xxxx",
            'totalItems' => $result['totalItems'],
            'totalPages' => $result['totalPages'],
            'page' => $result['currentPage'],
            'limit' => $result['limit'],
        ]);
    }
}
