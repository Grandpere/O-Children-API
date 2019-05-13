<?php

namespace App\Controller\Api;

use App\Entity\Category;
use Swagger\Annotations as SWG;
use App\Repository\CategoryRepository;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/api/categories", name="api_category_")
 */
class CategoryController extends AbstractController
{
    /**
     * @Route("/", name="get_All_Categories", methods={"GET"})
     * @SWG\Response(
     *  response=200,
     *  description="Retourne la liste des catégories",
     *  @SWG\Schema(
     *      type="array",
     *      @SWG\Items(ref=@Model(type=Category::class, groups={"category_list"}))
     *  )
     * )
     * @SWG\Tag(name="Categories")
     */
    public function read(CategoryRepository $categoryRepository, SerializerInterface $serializer)
    {
        $categories = $categoryRepository->findAll();
        $jsonCategories = $serializer->serialize($categories, 'json', ['groups' => 'category_list']);
        return JsonResponse::fromJsonString($jsonCategories);
    }

    /**
     *  @Route("/{id}", name="get_One_Category", methods={"GET"}, requirements={"id"="\d+"})
     *  @SWG\Response(
     *  response=200,
     *  description="Retourne la catégorie ciblée dans l'URL",
     *  @SWG\Schema(ref=@Model(type=Category::class, groups={"category_show"})))
     * )
     * @SWG\Response(
     *  response=404,
     *  description="Catégorie non trouvée",
     *  @SWG\Schema(
     *      @SWG\Property(
     *              property="code",
     *              type="integer",
     *              example="404"
     *          ),
     *          @SWG\Property(
     *              property="message",
     *              type="string",
     *              example="Catégorie non trouvée"
     *          )
     *  )
     * )
     * @SWG\Parameter(
     *     name="id",
     *     in="path",
     *     type="integer",
     *     description="L'identifiant de la catégorie"
     * )
     * @SWG\Tag(name="Categories")
     */
    public function readOne($id, CategoryRepository $categoryRepository, SerializerInterface $serializer)
    {
        $category = $categoryRepository->find($id);
        if (!$category) {
            return $this->json($data = ["code" => 404, "message" => "Catégorie non trouvée"], $status = 404);
        }
        $jsonCategory = $serializer->serialize($category, 'json', ['groups' => 'category_show']);
        return JsonResponse::fromJsonString($jsonCategory);
    }

    /**
     * @Route("/{id}/quizzs", name="get_quizzs", methods={"GET"}, requirements={"id"="\d+"})
     * @SWG\Response(
     *  response=200,
     *  description="Retourne tous les quizzs de la catégorie ciblée dans l'URL",
     *  @SWG\Schema(ref=@Model(type=Category::class, groups={"category_get_quizz"})))
     * )
     * @SWG\Response(
     *  response=404,
     *  description="Catégorie non trouvée",
     *  @SWG\Schema(
     *      @SWG\Property(
 *              property="code",
 *              type="integer",
 *              example="404"
 *          ),
 *          @SWG\Property(
 *              property="message",
 *              type="string",
 *              example="Catégorie non trouvée"
 *          )
     *  )
     * )
     * @SWG\Tag(name="Categories")
     */
    public function getActivities($id, CategoryRepository $categoryRepository, SerializerInterface $serializer)
    {
        $world = $categoryRepository->allQuizzs($id);
        if(!$world) { 
            return $this->json($data = ["code" => 404, "message" => "Catégorie non trouvée"], $status = 404);
        }
        $jsonWorld = $serializer->serialize($world, 'json', ['groups' => 'category_get_quizz']);
        return JsonResponse::fromJsonString($jsonWorld);
    }
}
