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

    public function resetPassword($user)
    {
        $message = (new \Swift_Message('Ochildren - Ton nouveau mot de passe'))
            ->setFrom('contact@ochildren.com')
            ->setTo($user->getEmail())
            ->setBody($this->twig->render('mailing/reset_password.html.twig', ['user' => $user]), 'text/html');
        return $this->mailer->send($message);
    }
}