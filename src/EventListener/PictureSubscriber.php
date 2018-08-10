<?php
/**
 * Created by PhpStorm.
 * User: jonathan
 * Date: 10/08/2018
 * Time: 15:50
 */

namespace App\EventListener;

use App\Entity\Picture;

class PictureSubscriber
{
    public function prePersist(Picture $picture)
    {
        $picture->setCreated(new \DateTime());
    }
}
