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
use Doctrine\ORM\Event\PreFlushEventArgs;

class TrickSubscriber implements EventSubscriber
{

    /**
     * Returns an array of events this subscriber wants to listen to.
     *
     * @return array
     */
    public function getSubscribedEvents()
    {
        return [
            'preUpdate',
            'preFlush',
        ];
    }

    public function preFlush(Trick $trick, PreFlushEventArgs $event)
    {
        $this->slugify($trick);
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function preUpdate(Trick $trick, LifecycleEventArgs $args)
    {
        // SET UPDATED DATE
        $trick->setUpdated(new \DateTime());
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
