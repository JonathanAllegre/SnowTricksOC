<?php
/**
 * Created by PhpStorm.
 * User: jonathan
 * Date: 10/07/2018
 * Time: 20:59
 */

namespace App\EventListener;

use App\Entity\Comment;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;

class CommentSuscriber implements EventSubscriber
{

    /**
     * Returns an array of events this subscriber wants to listen to.
     *
     * @return array
     */
    public function getSubscribedEvents()
    {
        return [
            'prePersist',
            ];
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        // only act on some "Trick" entity
        if (!$entity instanceof Comment) {
            return;
        }

        $entity->setCreated(new \DateTime());
    }
}
