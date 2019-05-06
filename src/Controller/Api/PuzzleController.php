<?php

namespace App\Controller\Api;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\PuzzleRepository;

/**
 * @Route("/api/puzzles", name="api_puzzle_")
 */
class PuzzleController extends AbstractController
{
    /**
     * @Route("/", name="get_All_Puzzles", methods={"GET"})
     */
    public function read(PuzzleRepository $puzzleRepository, SerializerInterface $serializer)
    {
        $puzzles = $puzzleRepository->findAll();
        $jsonPuzzles = $serializer->serialize($puzzles, 'json', ['groups' => 'puzzle_list']);
        return JsonResponse::fromJsonString($jsonPuzzles);
    }

    /**
     * @Route("/{id}", name="get_One_Puzzle", methods={"GET"})
     */
    public function readOne($id, PuzzleRepository $puzzleRepository, SerializerInterface $serializer)
    {
        $puzzle = $puzzleRepository->find($id);
        if(!$puzzle) {
            // return new JsonResponse(['data' => 123]); 
            return $this->json($data = ["code" => 404, "message" => "Puzzle non trouvé"], $status = 404);
        }
        $jsonPuzzle = $serializer->serialize($puzzle, 'json', ['groups' => 'puzzle_show']);
        return JsonResponse::fromJsonString($jsonPuzzle);
    }
}
