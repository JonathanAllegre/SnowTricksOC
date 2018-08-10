<?php
/**
 * Created by PhpStorm.
 * User: jonathan
 * Date: 10/08/2018
 * Time: 14:36
 */

namespace App\Service;


use App\Entity\Trick;
use App\Entity\Video;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Form\Form;

class VideoService
{

    private $doctrine;

    public function __construct(RegistryInterface $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    public function formHasVideo(Form $formTrick, Trick $trick)
    {
        if ($formTrick->get('videos')->getData()) {
            $videos = $formTrick->get('videos')->getData();
            foreach ($videos as $video) {
                dump($video);
                $video = (new Video())
                    ->setCreated(new \DateTime())
                    ->setUrl($video)
                    ->setTrick($trick);

                $this->doctrine->getManager()->persist($video);
                $this->doctrine->getManager()->flush();
            }
        }
    }
}