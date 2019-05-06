<?php

namespace App\Controller\Api;

use App\Repository\UserRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/api/users", name="api_user_")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/", name="get_All_Users", methods={"GET"})
     */
    public function read(UserRepository $userRepository, SerializerInterface $serializer)
    {
        $users = $userRepository->findAll();
        $jsonUsers = $serializer->serialize($users, 'json', ['groups' => 'user_list']);
        return JsonResponse::fromJsonString($jsonUsers);
    }

    /**
     * @Route("/{id}", name="get_One_User", methods={"GET"}, requirements={"id"="\d+"})
     */
    public function readOne($id, UserRepository $userRepository, SerializerInterface $serializer)
    {
        $user = $userRepository->find($id);
        if(!$user) {
            return $this->json($data = ["code" => 404, "message" => "Utilisateur non trouvé"], $status = 404);
        }
        $jsonUser = $serializer->serialize($user, 'json', ['groups' => 'user_show']);
        return JsonResponse::fromJsonString($jsonUser);
    }
}
