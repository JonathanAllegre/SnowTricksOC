<?php
/**
 * Created by PhpStorm.
 * User: jonathan
 * Date: 03/08/2018
 * Time: 08:06
 */

namespace App\Service;

use App\Entity\Picture;
use App\Entity\Trick;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class PictureService
{
    private $targetDirectory;
    private $manager;

    public function __construct($targetDirectory, ObjectManager $manager)
    {
        $this->targetDirectory = $targetDirectory;
        $this->manager         = $manager;
    }

    public function upload(UploadedFile $file)
    {
        $fileName = md5(uniqid()).'.'.$file->guessExtension();

        $file->move($this->getTargetDirectory(), $fileName);

        return $fileName;
    }

    public function getTargetDirectory()
    {
        return $this->targetDirectory;
    }


    /**
     * @param array $pictures
     * @param Trick $trick
     */
    public function savePicture(array $pictures, Trick $trick)
    {
        $pics = new ArrayCollection();
        foreach ($pictures as $picture) {
            $pic = (new Picture())->setName($this->upload($picture))->setTrick($trick);

            $pics->add($pic);
        }
        $trick->setPictures($pics);
    }

    /**
     * @param Picture $picture
     * @param Trick $trick
     */
    public function setListingPicture(Picture $picture, Trick $trick)
    {
        $trick->setListingPicture($picture);
        $this->manager->persist($trick);
        $this->manager->flush();
    }
}
