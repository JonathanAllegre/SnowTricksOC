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
    /**
     * @param Picture $picture
     */
    public function prePersist(Picture $picture)
    {
        $picture->setCreated(new \DateTime());
    }

    /**
     * @param Picture $picture
     */
    public function preRemove(Picture $picture)
    {
        $name = $picture->getName();

        unlink(__DIR__."/../../public/img/tricks/".$name);
    }
}
