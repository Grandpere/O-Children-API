<?php

namespace App\Utils;

use Twig\Environment as twig;

class MailGenerator
{
    private $mailer;

    public function __construct(\Swift_Mailer $mailer, twig $twig)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
    }

    public function newUser($user)
    {
        $message = (new \Swift_Message('Ochildren - Bienvenue sur le site'))
            ->setFrom('contact@ochildren.com')
            ->setTo($user->getEmail())
            ->setBody($this->twig->render('mailing/new_user.html.twig', ['user' => $user]), 'text/html');
        return $this->mailer->send($message);
    }

    public function resetPassword($user)
    {
        $message = (new \Swift_Message('Ochildren - Ton nouveau mot de passe'))
            ->setFrom('contact@ochildren.com')
            ->setTo($user->getEmail())
            ->setBody($this->twig->render('mailing/reset_password.html.twig', ['user' => $user]), 'text/html');
        return $this->mailer->send($message);
    }
}