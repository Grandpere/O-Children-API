<?php

namespace App\Controller\Api;

use App\Entity\User;
use Swagger\Annotations as SWG;
use App\Form\RegistrationFormType;
use App\Repository\RoleRepository;
use App\Repository\UserRepository;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use Symfony\Component\HttpFoundation\Request;
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
     * @Route("/{id}", name="get_One_User", methods={"GET"}, requirements={"id"="\d+"})
     * @SWG\Response(
     *  response=200,
     *  description="Retourne l'utilisateur ciblé dans l'URL",
     *  @SWG\Schema(ref=@Model(type=User::class, groups={"user_show"})))
     * )
     * @SWG\Response(
     *  response=404,
     *  description="Utilisateur non trouvé",
     *  @SWG\Schema(
     *      @SWG\Property(
 *              property="code",
 *              type="integer",
 *              example="404"
 *          ),
 *          @SWG\Property(
 *              property="message",
 *              type="string",
 *              example="Utilisateur non trouvé"
 *          )
     *  )
     * )
     * @SWG\Parameter(
     *     name="id",
     *     in="path",
     *     type="integer",
     *     description="L'identifiant de l'utilisateur"
     * )
     * @SWG\Tag(name="Users")
     * @Security(name="Bearer")
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
