<?php

namespace App\Controller\Api;

use App\Entity\Puzzle;
use Swagger\Annotations as SWG;
use App\Repository\PuzzleRepository;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/api/puzzles", name="api_puzzle_")
 */
class PuzzleController extends AbstractController
{
    /**
     * @Route("/{id}", name="get_One_Puzzle", methods={"GET"}, requirements={"id"="\d+"})
     * @SWG\Response(
     *  response=200,
     *  description="Retourne le puzzle ciblé dans l'URL",
     *  @SWG\Schema(ref=@Model(type=Puzzle::class, groups={"puzzle_show"})))
     * )
     * @SWG\Response(
     *  response=404,
     *  description="Puzzle non trouvé",
     *  @SWG\Schema(
     *      @SWG\Property(
 *              property="code",
 *              type="integer",
 *              example="404"
 *          ),
 *          @SWG\Property(
 *              property="message",
 *              type="string",
 *              example="Puzzle non trouvé"
 *          )
     *  )
     * )
     * @SWG\Parameter(
     *     name="id",
     *     in="path",
     *     type="integer",
     *     description="L'identifiant du puzzle"
     * )
     * @SWG\Tag(name="Puzzles")
     */
    public function readOne($id, PuzzleRepository $puzzleRepository, SerializerInterface $serializer)
    {
        $puzzle = $puzzleRepository->find($id);
        if(!$puzzle) {
            return $this->json($data = ["code" => 404, "message" => "Puzzle non trouvé"], $status = 404);
        }
        $jsonPuzzle = $serializer->serialize($puzzle, 'json', ['groups' => 'puzzle_show']);
        return JsonResponse::fromJsonString($jsonPuzzle);
    }
}
