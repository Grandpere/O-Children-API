<?php

namespace App\Controller\Api;

use App\Repository\WorldRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/api/world", name="api_world_")
 */
class WorldController extends AbstractController
{
    /**
     * @Route("/", name="worlds", methods={"GET"})
     */
    public function read(WorldRepository $worldRepository, SerializerInterface $serializer)
    {
        $worlds = $worldRepository->findAll();
        $jsonWorlds = $serializer->serialize($worlds, 'json', ['groups' => 'world_get']);
        return JsonResponse::fromJsonString($jsonWorlds);
    }
}
