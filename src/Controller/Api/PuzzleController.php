<?php

namespace App\Controller\Api;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\PuzzleRepository;

/**
 * @Route(name="api_puzzle_")
 */
class PuzzleController extends AbstractController
{
    /**
     * @Route("/api/puzzles", name="get_All_Puzzles", methods={"GET"})
     */
    public function read(PuzzleRepository $puzzleRepository, SerializerInterface $serializer)
    {
        $puzzles = $puzzleRepository->findAll();
        $jsonPuzzles = $serializer->serialize($puzzles, 'json', ['groups' => 'puzzle_list']);
        return JsonResponse::fromJsonString($jsonPuzzles);
    }

    /**
     * @Route("/api/puzzle/{id}", name="get_One_Puzzle", methods={"GET"})
     */
    public function readOne($id, PuzzleRepository $puzzleRepository, SerializerInterface $serializer)
    {
        $puzzle = $puzzleRepository->find($id);
        $jsonPuzzle = $serializer->serialize($puzzle, 'json', ['groups' => 'puzzle_show']);
        return JsonResponse::fromJsonString($jsonPuzzle);
    }
}
