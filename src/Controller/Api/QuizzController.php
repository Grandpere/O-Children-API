<?php

namespace App\Controller\Api;

use App\Repository\QuizzRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route(name="api_quizz_")
 */
class QuizzController extends AbstractController
{
    /**
     * @Route("/api/quizzs", name="get_All_Quizzs", methods={"GET"})
     */
    public function read(QuizzRepository $quizzRepository, SerializerInterface $serializer)
    {
        $quizzs = $quizzRepository->findAll();
        $jsonQuizzs = $serializer->serialize($quizzs, 'json', ['groups' => 'quizz_list']);
        return JsonResponse::fromJsonString($jsonQuizzs);
    }

    /**
     * @Route("/api/quizz/{id}", name="get_One_Quizz", methods={"GET"})
     */
    public function readOne($id, QuizzRepository $quizzRepository, SerializerInterface $serializer)
    {
        $quizz = $quizzRepository->find($id);
        $jsonQuizz = $serializer->serialize($quizz, 'json', ['groups' => 'quizz_show']);
        return JsonResponse::fromJsonString($jsonQuizz);
    }
}
