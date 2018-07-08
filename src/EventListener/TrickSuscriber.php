<?php
/**
 * Created by PhpStorm.
 * User: jonathan
 * Date: 08/07/2018
 * Time: 12:01
 */

namespace App\EventListener;

use Cocur\Slugify\Slugify;
use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use App\Entity\Trick;

class TrickSuscriber implements EventSubscriber
{

    /**
     * Returns an array of events this subscriber wants to listen to.
     *
     * @return array
     */
    public function getSubscribedEvents()
    {
        return array(
            'prePersist',
            'preUpdate',
        );
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        // only act on some "Trick" entity
        if (!$entity instanceof Trick) {
            return;
        }

        $this->slugify($entity);
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function preUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        // only act on some "Trick" entity
        if (!$entity instanceof Trick) {
            return;
        }

        // SLUGIFY
        $this->slugify($entity);

        // SET UPDATED DATE
        $entity->setUpdated(new \DateTime());
    }

    /**
     * @param Trick $trick
     */
    private function slugify(Trick $trick)
    {
        $slugify = new Slugify();

        $trick->getName();
        $trick->setSlug($slugify->slugify($trick->getName()));
    }
}
