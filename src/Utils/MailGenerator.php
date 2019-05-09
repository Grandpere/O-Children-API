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
            ->setFrom('lorenzo.marozzo@gmail.com')
            // ->setTo($user->getEmail())
            ->setTo('lorenzo.marozzo@gmail.com')
            ->setBody('<h1>coucou</h1>');
            // ->setBody($this->twig->render('mailing/resetpassword.html.twig', ['user' => $user]), 'text/html');
        return $this->mailer->send($message);
    }
}