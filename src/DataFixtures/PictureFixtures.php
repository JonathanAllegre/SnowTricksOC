<?php

namespace App\DataFixtures;

use App\Entity\Picture;
use App\Entity\Trick;
use App\Repository\TrickRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class PictureFixtures extends Fixture implements DependentFixtureInterface
{
    private $manager;
    private $trickRepo;

    public function __construct(ObjectManager $manager, TrickRepository $trickRepo)
    {
        $this->manager   = $manager;
        $this->trickRepo = $trickRepo;
    }

    public function load(ObjectManager $manager)
    {
        $pictureFile = file(__DIR__.'/ContentFiles/picture.txt');

        $this->newPicture(
            trim($pictureFile[0]),
            $this->getReference(TrickFixtures::TRICK_MUTE),
            true
        );

        $this->newPicture(
            trim($pictureFile[1]),
            $this->getReference(TrickFixtures::TRICK_MUTE)
        );

        $this->newPicture(
            trim($pictureFile[2]),
            $this->getReference(TrickFixtures::TRICK_STALEFISH),
            true

        );

        $this->newPicture(
            trim($pictureFile[3]),
            $this->getReference(TrickFixtures::TRICK_STALEFISH)
        );

        $this->newPicture(
            trim($pictureFile[4]),
            $this->getReference(TrickFixtures::TRICK_180),
            true
        );

        $this->newPicture(
            trim($pictureFile[5]),
            $this->getReference(TrickFixtures::TRICK_180)
        );

        $this->newPicture(
            trim($pictureFile[6]),
            $this->getReference(TrickFixtures::TRICK_BACKFLIP),
            true
        );

        $this->newPicture(
            trim($pictureFile[7]),
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
     * @param $name
     * @param $trick
     * @return Picture
     */
    public function newPicture($name, Trick $trick, $listing = false):Picture
    {
        $picture = new Picture();
        $picture->setName($name);
        $picture->setCreated(new \DateTime());
        $picture->setTrick($trick);

        $this->manager->persist($picture);

        if ($listing) {
            $trick->setListingPicture($picture);
        }

        return $picture;
    }
}
