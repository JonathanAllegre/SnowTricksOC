<?php

namespace App\DataFixtures;

use App\Entity\Trick;
use App\Entity\Video;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class VideoFixtures extends Fixture implements DependentFixtureInterface
{
    private $manager;

    public function __construct(ObjectManager $manager)
    {
        $this->manager = $manager;
    }

    public function load(ObjectManager $manager)
    {
        $videoFile = file(__DIR__.'/video.txt');

        $this->newVideo(
            trim($videoFile[0]),
            $this->getReference(TrickFixtures::TRICK_MUTE)
        );

        $this->newVideo(
            trim($videoFile[1]),
            $this->getReference(TrickFixtures::TRICK_MUTE)
        );

        $this->newVideo(
            trim($videoFile[2]),
            $this->getReference(TrickFixtures::TRICK_STALEFISH)
        );

        $this->newVideo(
            trim($videoFile[3]),
            $this->getReference(TrickFixtures::TRICK_BACKFLIP)
        );

        $manager->flush();
    }

    /**
     * This method must return an array of fixtures classes
     * on which the implementing class depends on
     *
     * @return array
     */
    public function getDependencies()
    {
        return [
            TrickFixtures::class,
        ];
    }

    /**
     * @param $url
     * @param $trick
     * @return Video
     */
    public function newVideo($url, Trick $trick):Video
    {
        $video = new Video();
        $video->setUrl($url);
        $video->setCreated(new \DateTime());
        $video->setTrick($trick);

        $this->manager->persist($video);

        return $video;
    }
}
