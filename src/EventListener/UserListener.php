<?php

namespace App\EventListener;

use App\Entity\User;
use App\Utils\MailGenerator;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserListener
{
    private $passwordEncoder;
    private $mailGenerator;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder, MailGenerator $mailGenerator)
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->mailGenerator = $mailGenerator;
    }
    
    public function prePersist(LifecycleEventArgs $args)
    {
        $this->hashPassword($args);
    }

    public function postPersist(LifecycleEventArgs $args)
    {
        $this->sendMAilPostRegister($args);
    }

    // public function preUpdate(LifecycleEventArgs $args)
    // {
    //     $this->hashPassword($args);
    //     // encode le pwd à chaque changement dans user donc même pour favoris, à revoir pour optimiser si possible
    // }

    public function sendMAilPostRegister(LifecycleEventArgs $args)
    {
        $user = $args->getObject();

        if ($user instanceof User) {
            $this->mailGenerator->newUser($user);
        }
    }

    public function hashPassword(LifecycleEventArgs $args)
    {
        $user = $args->getObject();

        if ($user instanceof User) {
            $encodedPassword = $this->passwordEncoder->encodePassword($user, $user->getPassword());
            $user->setPassword($encodedPassword);
        }
    }
}