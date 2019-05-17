<?php

namespace App\Controller;

use App\Utils\MailGenerator;
use Swagger\Annotations as SWG;
use App\Utils\PasswordGenerator;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserPasswordController extends AbstractController
{
    /**
     * @Route("/api/password/forgotten", name="forgotten_password", methods={"POST"})
     * @SWG\Response(
     *  response=200,
     *  description="Mail envoyé avec le nouveau mot de passe",
     * @SWG\Schema(
     *      @SWG\Property(
 *              property="code",
 *              type="integer",
 *              example="200"
 *          ),
 *          @SWG\Property(
 *              property="message",
 *              type="string",
 *              example="Mot de passe envoyé"
 *          )
     *  )
     * )
     * @SWG\Response(
     *  response=400,
     *  description="Le formulaire n'est pas correct",
     * @SWG\Schema(
     *      @SWG\Property(
 *              property="code",
 *              type="integer",
 *              example="400"
 *          ),
 *          @SWG\Property(
 *              property="message",
 *              type="string",
 *              example="Le formulaire doit contenir un champ dont le name est email"
 *          )
     *  )
     * )
     * @SWG\Response(
     *  response=404,
     *  description="Utilisateur non trouvé",
     * @SWG\Schema(
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
    public function newPassword(Request $request, UserRepository $userRepository, PasswordGenerator $pwdGenerator, MailGenerator $mailGenerator) {
        $content = $request->getContent();
        $jsonObj = json_decode($content);
        try {
            $email = $jsonObj->email;
        } catch (\Throwable $th) {
            return $this->json($data = ["code" => 400, "message" => "Le formulaire doit contenir un champ dont le name est email"], $status = 400); 
        } 

        $user = $userRepository->findOneByEmail($email);
        if (!$user) {
            return $this->json($data = ["code" => 404, "message" => "Utilisateur non trouvé"], $status = 404);
        }
        $user->setPassword($pwdGenerator->generate());
        $user->setUpdatedAt(new \DateTime());
        $mailGenerator->resetPassword($user);
        $em = $this->getDoctrine()->getManager();
        $em->flush();
        return $this->json($data = ["code" => 200, "message" => "Mot de passe envoyé"], $status = 200);    
        // TODO: voir pour optimiser cela car un autre user peut réinitialiser le pwd d'un autre, il n'y aura pas accès sauf s'il a accès à la boite mail de l'user, il faut faire un controle donc décaler la réinitialisation du password (cf mail activation account prévu)   
    }
}
