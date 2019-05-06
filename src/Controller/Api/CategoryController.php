<?php

namespace App\Controller\Api;

use App\Repository\CategoryRepository;
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
     */
    public function read(CategoryRepository $categoryRepository, SerializerInterface $serializer)
    {
        $categories = $categoryRepository->findAll();
        $jsonCategories = $serializer->serialize($categories, 'json', ['groups' => 'category_list']);
        return JsonResponse::fromJsonString($jsonCategories);
    }

    /**
     * @Route("/{id}", name="get_One_Category", methods={"GET"})
     */
    public function readOne($id, CategoryRepository $categoryRepository, SerializerInterface $serializer)
    {
        $category = $categoryRepository->find($id);

        if(!$category) {
            // return new JsonResponse(['data' => 123]); 
            return $this->json($data = ["code" => 404, "message" => "Catégorie non trouvée"], $status = 404);
        }
        $jsonCategory = $serializer->serialize($category, 'json', ['groups' => 'category_show']);
        return JsonResponse::fromJsonString($jsonCategory);
    }
}
