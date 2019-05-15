<?php

namespace App\Controller\Api;

use App\Entity\World;
use Swagger\Annotations as SWG;
use App\Repository\WorldRepository;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
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
     * @SWG\Response(
     *  response=200,
     *  description="Retourne la liste des mondes",
     *  @SWG\Schema(
     *      type="array",
     *      @SWG\Items(ref=@Model(type=World::class, groups={"world_list"}))
     *  )
     * )
     * @SWG\Tag(name="Worlds")
     */
    public function read(WorldRepository $worldRepository, SerializerInterface $serializer)
    {
        $worlds = $worldRepository->findAll();
        $jsonWorlds = $serializer->serialize($worlds, 'json', ['groups' => 'world_list']);
        return JsonResponse::fromJsonString($jsonWorlds);
    }

    /**
     * @Route("/{id}", name="get_One_World", methods={"GET"}, requirements={"id"="\d+"})
     * @SWG\Response(
     *  response=200,
     *  description="Retourne le monde ciblé dans l'URL",
     *  @SWG\Schema(ref=@Model(type=World::class, groups={"world_show"})))
     * )
     * @SWG\Response(
     *  response=404,
     *  description="Monde non trouvé",
     *  @SWG\Schema(
     *      @SWG\Property(
     *              property="code",
     *              type="integer",
     *              example="404"
     *          ),
     *          @SWG\Property(
     *              property="message",
     *              type="string",
     *              example="Monde non trouvé"
     *          )
     *  )
     * )
     * @SWG\Tag(name="Worlds")
     */ 
    public function readOne($id, WorldRepository $worldRepository, SerializerInterface $serializer)
    {
        $world = $worldRepository->find($id);
        if(!$world) { 
            return $this->json($data = ["code" => 404, "message" => "Monde non trouvé"], $status = 404);
        }
        $jsonWorld = $serializer->serialize($world, 'json', ['groups' => 'world_show']);
        return JsonResponse::fromJsonString($jsonWorld);
    }

    /**
     * @Route("/{id}/quizzs", name="get_quizzs", methods={"GET"}, requirements={"id"="\d+"})
     * @SWG\Response(
     *  response=200,
     *  description="Retourne tous les quizzs du monde ciblé dans l'URL",
     *  @SWG\Schema(ref=@Model(type=World::class, groups={"world_get_quizz"})))
     * )
     * @SWG\Response(
     *  response=404,
     *  description="Monde non trouvé",
     *  @SWG\Schema(
     *      @SWG\Property(
 *              property="code",
 *              type="integer",
 *              example="404"
 *          ),
 *          @SWG\Property(
 *              property="message",
 *              type="string",
 *              example="Monde non trouvé"
 *          )
     *  )
     * )
     * @SWG\Tag(name="Worlds")
     */
    public function getQuizzs($id, WorldRepository $worldRepository, SerializerInterface $serializer)
    {
        $world = $worldRepository->allQuizzs($id);
        if(!$world) { 
            return $this->json($data = ["code" => 404, "message" => "Monde non trouvé"], $status = 404);
        }
        $jsonWorld = $serializer->serialize($world, 'json', ['groups' => 'world_get_quizz']);
        return JsonResponse::fromJsonString($jsonWorld);
    }

        /**
     * @Route("/{id}/puzzles", name="get_puzzles", methods={"GET"}, requirements={"id"="\d+"})
     * @SWG\Response(
     *  response=200,
     *  description="Retourne tous les puzzles du monde ciblé dans l'URL",
     *  @SWG\Schema(ref=@Model(type=World::class, groups={"world_get_puzzle"})))
     * )
     * @SWG\Response(
     *  response=404,
     *  description="Monde non trouvé",
     *  @SWG\Schema(
     *      @SWG\Property(
 *              property="code",
 *              type="integer",
 *              example="404"
 *          ),
 *          @SWG\Property(
 *              property="message",
 *              type="string",
 *              example="Monde non trouvé"
 *          )
     *  )
     * )
     * @SWG\Tag(name="Worlds")
     */
    public function getPuzzles($id, WorldRepository $worldRepository, SerializerInterface $serializer)
    {
        $world = $worldRepository->allPuzzles($id);
        if(!$world) { 
            return $this->json($data = ["code" => 404, "message" => "Monde non trouvé"], $status = 404);
        }
        $jsonWorld = $serializer->serialize($world, 'json', ['groups' => 'world_get_puzzle']);
        return JsonResponse::fromJsonString($jsonWorld);
    }
}
