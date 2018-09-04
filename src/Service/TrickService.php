<?php
/**
 * Created by PhpStorm.
 * User: jonathan
 * Date: 03/08/2018
 * Time: 08:07
 */

namespace App\Service;

use App\Entity\Trick;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Security\Core\Security;

class TrickService
{
    private $videoService;
    private $pictureService;

    /**
     * TrickService constructor.
     * @param RegistryInterface $doctrine
     * @param Security $security
     */
    public function __construct(
        PictureService $pictureService,
        VideoService $videoService
    ) {
        $this->pictureService = $pictureService;
        $this->videoService = $videoService;
    }

    /**
     * @param FormInterface $form
     * @param Trick $trick
     * @return bool
     */
    public function trickHasPicture(Trick $trick)
    {
        if ($trick->getPictures()) {
            $this->pictureService->savePicture($trick->getPictures(), $trick);

            return true;
        }

        return false;
    }
}
