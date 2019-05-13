<?php

namespace App\Controller\Api;

use App\Entity\User;
use Swagger\Annotations as SWG;
use App\Repository\UserRepository;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use PhpParser\Node\Stmt\TryCatch;

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

    /**
     * @Route("/{id}/password/update", name="updatePwd", methods={"POST"}, requirements={"id"="\d+"})
     * @SWG\Response(
     *  response=200,
     *  description="Mot de passe modifié avec succès",
     * )
     * @SWG\Tag(name="Users")
     * // TODO: doc à faire
     */
    public function updatePassword($id, Request $request, SerializerInterface $serializer, UserRepository $userRepository)
    {
        $user = $userRepository->find($id);
        if(!$user) {
            return $this->json($data = ["code" => 404, "message" => "Utilisateur non trouvé"], $status = 404);
        }
        $content = $request->getContent();
        $jsonObj = json_decode($content);

        try {
            $actualPwd = $jsonObj->password;
            $newPwd1 = $jsonObj->plainpassword;
            $newPwd2 = $jsonObj->plainpassword2;
        } catch (\Throwable $th) {
            return $this->json($data = ["code" => 400, "message" => "Le formulaire doit contenir les champs dont le name est password, plainpassword et plainpassword2"], $status = 400); 
        }        

        if(empty($actualPwd)) {
            return $this->json($data = ["code" => 403, "message" => "Vous devez confirmer votre mot de passe actuel pour modifier le mot de passe"], $status = 403); 
        }

        if($newPwd1 != $newPwd2) {
            return $this->json($data = ["code" => 418, "message" => "I'm a teapot, les deux nouveaux mot de passe ne sont pas identiques"], $status = 418); 
        }

        if(password_verify($actualPwd, $user->getPassword())) {
            $user->setPassword($newPwd1);
            $user->setUpdatedAt(new \DateTime());
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            return $this->json($data = ["code" => 200, "message" => "Le mot de passe a été modifié"], $status = 200);  
        }
        return $this->json($data = ["code" => 403, "message" => "Le mot de passe actuel est incorrect"], $status = 403);  
    }
}
