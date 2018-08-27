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
        if (!filter_var($picture->getName(), FILTER_VALIDATE_URL)) {
            unlink(__DIR__."/../../public/img/tricks/".$picture->getName());
        }
    }
}
