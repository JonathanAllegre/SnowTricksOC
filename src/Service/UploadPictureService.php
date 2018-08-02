<?php
/**
 * Created by PhpStorm.
 * User: jonathan
 * Date: 02/08/2018
 * Time: 07:40
 */

namespace App\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class UploadPictureService
{

    private $targetDirectory;

    public function __construct($targetDirectory)
    {
        $this->targetDirectory = $targetDirectory;
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
}
