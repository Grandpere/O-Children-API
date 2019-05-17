<?php

namespace App\Controller;

use App\Entity\User;
use Swagger\Annotations as SWG;
use App\Repository\RoleRepository;
use App\Repository\UserRepository;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RegistrationController extends AbstractController
{
    /**
     * @Route("api/signup", name="register", methods={"POST"})
     * @SWG\Response(
     *  response=201,
     *  description="Utilisateur crée avec succès",
     *  @SWG\Schema(
     *      @SWG\Property(
 *              property="code",
 *              type="integer",
 *              example="201"
 *          ),
 *          @SWG\Property(
 *              property="message",
 *              type="string",
 *              example="Inscription validée avec succès"
 *          ),
 *          @SWG\Property(
 *              property="id",
 *              type="integer",
 *              example="1"
 *          )
     *  )
     * )
     * @SWG\Response(
     *  response=409,
     *  description="Un compte existe déja pour cet email",
     *  @SWG\Schema(
     *      @SWG\Property(
 *              property="code",
 *              type="integer",
 *              example="201"
 *          ),
 *          @SWG\Property(
 *              property="message",
 *              type="string",
 *              example="Un compte existe déja pour cet email"
 *          ),
     *  )
     * )
     * @SWG\Parameter(
     *     name="email",
     *     in="query",
     *     type="string",
     *     description="email de l'utilisateur"
     * ),
     * @SWG\Parameter(
     *     name="plainpassword",
     *     in="query",
     *     type="string",
     *     description="mot de passe de l'utilisateur"
     * ),
     * @SWG\Parameter(
     *     name="plainpassword2",
     *     in="query",
     *     type="string",
     *     description="confirmation du mot de passe de l'utilisateur"
     * ),
     * @SWG\Parameter(
     *     name="username",
     *     in="query",
     *     type="string",
     *     description="pseudo de l'utilisateur"
     * )
     * @SWG\Tag(name="Users")
     */
    public function register(Request $request, SerializerInterface $serializer, RoleRepository $roleRepository, UserRepository $userRepository)
    {
        $user = new User();

        $content = $request->getContent();
        $user = $serializer->deserialize($content, 'App\Entity\User', 'json');

        $user->setEmail(trim($user->getEmail()));
        $user->setPlainpassword(trim($user->getPlainpassword()));
        $user->setPlainpassword2(trim($user->getPlainpassword2()));
        
        if($user->getPlainpassword() == $user->getPlainpassword2()) {
            $user->setPassword($user->getPlainpassword());
        }
        
        $existingUser = $userRepository->findOneByEmail($user->getEmail());
        if($existingUser) {
            return $this->json($data = ["code" => 409, "message" => "Un compte existe déja pour cet email"]);
        }

        $user->setImage("https://robohash.org/".$user->getEmail()."?set=set".mt_rand(1, 4));
        $entityManager = $this->getDoctrine()->getManager();
        $role = $roleRepository->findOneByName('ROLE_USER');
        $user->setRole($role);
        $entityManager->persist($user);
        $entityManager->flush();

        return $this->json($data = ["code" => 201, "message" => "Inscription validée avec succès", "id" => $user->getId()], $status = 201);
    }
}