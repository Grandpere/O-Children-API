<?php

namespace App\Controller\Api;

use App\Entity\User;
use Swagger\Annotations as SWG;
use PhpParser\Node\Stmt\TryCatch;
use App\Repository\UserRepository;
use App\Repository\QuizzRepository;
use App\Repository\PuzzleRepository;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

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
     * @SWG\Response(
     *  response=401,
     *  description="Authentification requise pour accèder aux données, token requis",
     *  @SWG\Schema(
     *      @SWG\Property(
 *              property="code",
 *              type="integer",
 *              example="401"
 *          ),
 *          @SWG\Property(
 *              property="message",
 *              type="string",
 *              example="JWT Token not found"
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
     * @SWG\Schema(
     *      @SWG\Property(
 *              property="code",
 *              type="integer",
 *              example="200"
 *          ),
 *          @SWG\Property(
 *              property="message",
 *              type="string",
 *              example="Le mot de passe a été modifié"
 *          )
     *  )
     * )
     * @SWG\Response(
     *  response=400,
     *  description="Le formulaire n'est pas correct",
     *  @SWG\Schema(
     *      @SWG\Property(
 *              property="code",
 *              type="integer",
 *              example="400"
 *          ),
 *          @SWG\Property(
 *              property="message",
 *              type="string",
 *              example="Le formulaire doit contenir les champs dont le name est password, plainpassword et plainpassword2"
 *          )
     *  )
     * )
     * @SWG\Response(
     *  response=401,
     *  description="Authentification requise pour accèder aux données, token requis",
     *  @SWG\Schema(
     *      @SWG\Property(
 *              property="code",
 *              type="integer",
 *              example="401"
 *          ),
 *          @SWG\Property(
 *              property="message",
 *              type="string",
 *              example="JWT Token not found"
 *          )
     *  )
     * )
     *@SWG\Response(
     *  response=403,
     *  description="Authentification requise",
     *  @SWG\Schema(
     *      @SWG\Property(
 *              property="code",
 *              type="integer",
 *              example="403"
 *          ),
 *          @SWG\Property(
 *              property="message",
 *              type="string",
 *              example="Le mot de passe actuel est incorrect ou n'a pas été renseigné"
 *          )
     *  )
     * )
     *@SWG\Response(
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
     * @SWG\Tag(name="Users")
     */
    public function updatePassword($id, Request $request, SerializerInterface $serializer, UserRepository $userRepository, UserPasswordEncoderInterface $passwordEncoder)
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
            $encodedPassword = $passwordEncoder->encodePassword($user, $newPwd1);
            $user->setPassword($encodedPassword);
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            return $this->json($data = ["code" => 200, "message" => "Le mot de passe a été modifié"], $status = 200);  
        }
        return $this->json($data = ["code" => 403, "message" => "Le mot de passe actuel est incorrect"], $status = 403);  
    }

    /**
     * @Route("/{userId}/bookmarks/quizzs/{id}/toggle", name="quizz_bookmark", methods={"POST"}, requirements={"userId"="\d+", "id"="\d+"})
     */
    public function addQuizzBookmark($userId, $id, UserRepository $userRepository, QuizzRepository $quizzRepository)
    {
        $user = $userRepository->find($userId);
        if(!$user) {
            return $this->json($data = ["code" => 404, "message" => "Utilisateur non trouvé"], $status = 404);
        }
        $quizz = $quizzRepository->find($id);
        if(!$quizz) {
            return $this->json($data = ["code" => 404, "message" => "Quizz non trouvé"], $status = 404);
        }

        $em = $this->getDoctrine()->getManager();
        $quizzBookmarks = $user->getQuizzBookmarks();
        if($quizzBookmarks->contains($quizz)) {
            $user->removeQuizzBookmark($quizz);
            $em->flush();
            return $this->json($data = ["code" => 200, "message" => "Favoris supprimé avec succès"], $status = 200); 
        }
        $user->addQuizzBookmark($quizz);
        $em->flush();
        return $this->json($data = ["code" => 200, "message" => "Favoris ajouté avec succès"], $status = 200);      
    }

        /**
     * @Route("/{userId}/bookmarks/puzzles/{id}/toggle", name="puzzle_bookmark", methods={"POST"}, requirements={"userId"="\d+", "id"="\d+"})
     */
    public function addPuzzleBookmark($userId, $id, UserRepository $userRepository, PuzzleRepository $puzzleRepository)
    {
        $user = $userRepository->find($userId);
        if(!$user) {
            return $this->json($data = ["code" => 404, "message" => "Utilisateur non trouvé"], $status = 404);
        }
        $puzzle = $puzzleRepository->find($id);
        if(!$puzzle) {
            return $this->json($data = ["code" => 404, "message" => "Puzzle non trouvé"], $status = 404);
        }

        $em = $this->getDoctrine()->getManager();
        $puzzleBookmarks = $user->getPuzzleBookmarks();
        if($puzzleBookmarks->contains($puzzle)) {
            $user->removePuzzleBookmark($puzzle);
            $em->flush();
            return $this->json($data = ["code" => 200, "message" => "Favoris supprimé avec succès"], $status = 200); 
        }
        $user->addPuzzleBookmark($puzzle);
        $em->flush();
        return $this->json($data = ["code" => 200, "message" => "Favoris ajouté avec succès"], $status = 200);      
    }
}
