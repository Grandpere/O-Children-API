<?php

namespace App\Controller\Api;

use App\Repository\QuizzRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/api/quizzs", name="api_quizz_")
 */
class QuizzController extends AbstractController
{
    /**
     * @Route("/", name="get_All_Quizzs", methods={"GET"})
     */
    public function read(QuizzRepository $quizzRepository, SerializerInterface $serializer)
    {
        $quizzs = $quizzRepository->findAll();
        $jsonQuizzs = $serializer->serialize($quizzs, 'json', ['groups' => 'quizz_list']);
        return JsonResponse::fromJsonString($jsonQuizzs);
    }

    /**
     * @Route("/{id}", name="get_One_Quizz", methods={"GET"})
     */
    public function readOne($id, QuizzRepository $quizzRepository, SerializerInterface $serializer)
    {
        $quizz = $quizzRepository->find($id);
        if(!$quizz) {
            // return new JsonResponse(['data' => 123]); 
            return $this->json($data = ["code" => 404, "message" => "Quizz non trouvé"], $status = 404);
        }
        $jsonQuizz = $serializer->serialize($quizz, 'json', ['groups' => 'quizz_show']);
        return JsonResponse::fromJsonString($jsonQuizz);
    }
}
