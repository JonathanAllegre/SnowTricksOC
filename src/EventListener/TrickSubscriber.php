<?php
/**
 * Created by PhpStorm.
 * User: jonathan
 * Date: 08/07/2018
 * Time: 12:01
 */

namespace App\EventListener;

use App\Entity\Picture;
use Cocur\Slugify\Slugify;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use App\Entity\Trick;
use Doctrine\ORM\Event\PreFlushEventArgs;

class TrickSubscriber
{

    public function preFlush(Trick $trick)
    {
        $this->slugify($trick);
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function preUpdate(Trick $trick)
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
