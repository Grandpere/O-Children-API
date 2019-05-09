<?php

namespace App\Controller;

use Swagger\Annotations as SWG;
use App\Utils\PasswordGenerator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\UserRepository;

class UserPasswordController extends AbstractController
{
    /**
     * @Route("/api/password/forgotten", name="forgotten_password", methods={"POST"})
     * @SWG\Response(
     *  response=200,
     *  description="Mail envoyé avec le nouveau mot de passe"
     * )
     * @SWG\Tag(name="Users")
     */
    public function newPassword(Request $request, UserRepository $userRepository, PasswordGenerator $pwdGenerator) {
        $content = $request->getContent();
        $jsonObj = json_decode($content);
        $email = $jsonObj->email;

        $user = $userRepository->findOneByEmail($email);

        if (!$user) {
            return $this->json($data = ["code" => 404, "message" => "Utilisateur non trouvé"], $status = 404);
        }
        $user->setPassword($pwdGenerator->generate());
        $user->setUpdatedAt(new \DateTime());
        // $mailGenerator->resetPassword($user);
        $em = $this->getDoctrine()->getManager();
        $em->flush();
        dd($user);
        return $this->json($data = ["code" => 200, "message" => "Mot de passe envoyé"], $status = 200);       
    }
}
