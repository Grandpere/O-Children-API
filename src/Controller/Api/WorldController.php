<?php

namespace App\Controller\Api;

use App\Repository\WorldRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/api/worlds", name="api_world_")
 */
class WorldController extends AbstractController
{
    /**
     * @Route("/", name="get_All_World", methods={"GET"})
     */
    public function read(WorldRepository $worldRepository, SerializerInterface $serializer)
    {
        $worlds = $worldRepository->findAll();
        $jsonWorlds = $serializer->serialize($worlds, 'json', ['groups' => 'world_list']);
        return JsonResponse::fromJsonString($jsonWorlds);
    }

    /**
     * @Route("/{id}", name="get_One_World", methods={"GET"})
     */
    public function readOne($id, WorldRepository $worldRepository, SerializerInterface $serializer)
    {
        $world = $worldRepository->find($id);
        if(!$world) {
            // return new JsonResponse(['data' => 123]); 
            return $this->json($data = ["code" => 404, "message" => "Monde non trouvé"], $status = 404);
        }
        $jsonWorld = $serializer->serialize($world, 'json', ['groups' => 'world_show']);
        return JsonResponse::fromJsonString($jsonWorld);
    }
}
