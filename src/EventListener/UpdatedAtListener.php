<?php

namespace App\EventListener;

use App\Entity\User;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;

class UpdatedAtListener
{
    public function preUpdate(LifecycleEventArgs $args)
    {
        $this->updateColumnUpdatedAt($args);
    }

    public function updateColumnUpdatedAt(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();
        $entity->setUpdatedAt(new \DateTime());
    }
}