<?php
/**
 * Created by PhpStorm.
 * User: jonathan
 * Date: 10/08/2018
 * Time: 15:57
 */

namespace App\EventListener;

use App\Entity\Video;

class VideoSubscriber
{
    public function prePersist(Video $video)
    {
        $video->setCreated(new \DateTime());
    }
}
