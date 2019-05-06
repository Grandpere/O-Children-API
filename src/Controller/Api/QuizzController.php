<?php

namespace App\Controller\Api;

use App\Entity\Quizz;
use Swagger\Annotations as SWG;
use App\Repository\QuizzRepository;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
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
     * @SWG\Response(
     *  response=200,
     *  description="Retourne la liste des quizzs",
     *  @SWG\Schema(
     *      type="array",
     *      @SWG\Items(ref=@Model(type=Quizz::class, groups={"quizz_list"}))
     *  )
     * )
     * @SWG\Tag(name="Quizzs")
     * @Security(name="Bearer")
     */
    public function read(QuizzRepository $quizzRepository, SerializerInterface $serializer)
    {
        $quizzs = $quizzRepository->findAll();
        $jsonQuizzs = $serializer->serialize($quizzs, 'json', ['groups' => 'quizz_list']);
        return JsonResponse::fromJsonString($jsonQuizzs);
    }

    /**
     * @Route("/{id}", name="get_One_Quizz", methods={"GET"}, requirements={"id"="\d+"})
     * @SWG\Response(
     *  response=200,
     *  description="Retourne le quizz ciblé dans l'URL",
     *  @SWG\Schema(ref=@Model(type=Quizz::class, groups={"quizz_show"})))
     * )
     * @SWG\Response(
     *  response=404,
     *  description="Quizz non trouvé",
     *  @SWG\Schema(
     *      @SWG\Property(
 *              property="code",
 *              type="integer",
 *              example="404"
 *          ),
 *          @SWG\Property(
 *              property="message",
 *              type="string",
 *              example="Quizz non trouvé"
 *          )
     *  )
     * )
     * @SWG\Parameter(
     *     name="id",
     *     in="path",
     *     type="integer",
     *     description="L'identifiant du quizz"
     * )
     * @SWG\Tag(name="Quizzs")
     * @Security(name="Bearer")
     */
    public function readOne($id, QuizzRepository $quizzRepository, SerializerInterface $serializer)
    {
        $quizz = $quizzRepository->find($id);
        if(!$quizz) {
            return $this->json($data = ["code" => 404, "message" => "Quizz non trouvé"], $status = 404);
        }
        $jsonQuizz = $serializer->serialize($quizz, 'json', ['groups' => 'quizz_show']);
        return JsonResponse::fromJsonString($jsonQuizz);
    }
}
