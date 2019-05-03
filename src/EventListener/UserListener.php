<?php

namespace App\EventListener;

use App\Entity\User;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserListener
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }
    
    public function prePersist(LifecycleEventArgs $args)
    {
        $this->hashPassword($args);
    }

    public function preUpdate(LifecycleEventArgs $args)
    {
        $this->hashPassword($args);
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