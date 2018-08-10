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
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class PictureService
{
    private $targetDirectory;
    private $doctrine;

    public function __construct($targetDirectory, RegistryInterface $doctrine)
    {
        $this->targetDirectory = $targetDirectory;
        $this->doctrine        = $doctrine;
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

    public function formHasPicture(Form $form, Trick $trick)
    {
        if ($form->get('image')->getData()) {
            $this->persist($form->get('image')->getData(), $trick);
        }

        return false;
    }

    private function persist($pictures, Trick $trick)
    {
        foreach ($pictures as $picture) {
            $imgUploaded = $this->upload($picture);
            $picture = (new Picture())
                ->setName($imgUploaded)
                ->setTrick($trick);

            $this->doctrine->getManager()->persist($picture);
            $this->doctrine->getManager()->flush();
        }
    }
}
