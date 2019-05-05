<?php

namespace App\Controller\Api;

use App\Repository\WorldRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route(name="api_world_")
 */
class WorldController extends AbstractController
{
    /**
     * @Route("/api/worlds", name="get_All_World", methods={"GET"})
     */
    public function read(WorldRepository $worldRepository, SerializerInterface $serializer)
    {
        $worlds = $worldRepository->findAll();
        $jsonWorlds = $serializer->serialize($worlds, 'json', ['groups' => 'world_list']);
        return JsonResponse::fromJsonString($jsonWorlds);
    }

    /**
     * @Route("/api/world/{id}", name="get_One_World", methods={"GET"})
     */
    public function readOne($id, WorldRepository $worldRepository, SerializerInterface $serializer)
    {
        $world = $worldRepository->find($id);
        $jsonWorld = $serializer->serialize($world, 'json', ['groups' => 'world_show']);
        return JsonResponse::fromJsonString($jsonWorld);
    }
}
