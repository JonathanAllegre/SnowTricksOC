<?php

namespace App\DataFixtures;

use App\Entity\Trick;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class TrickFixtures extends Fixture implements DependentFixtureInterface
{

    private $manager;


    public function __construct(ObjectManager $manager)
    {
        $this->manager = $manager;
    }

    public function load(ObjectManager $manager)
    {
        $trickDescription = file(__DIR__.'/trickDescription.txt');


        $trick1 = $this->newTrick(
            'Mute',
            trim($trickDescription[0]),
            $this->getReference(UserFixtures::USER_ONE_REFERENCE),
            $this->getReference(FamilyFixtures::GRAB_FAMILY)
        );

        $trick2 = $this->newTrick(
            'Stalefish',
            trim($trickDescription[1]),
            $this->getReference(UserFixtures::USER_ONE_REFERENCE),
            $this->getReference(FamilyFixtures::GRAB_FAMILY)
        );

        $trick3 = $this->newTrick(
            '180',
            trim($trickDescription[2]),
            $this->getReference(UserFixtures::USER_ONE_REFERENCE),
            $this->getReference(FamilyFixtures::ROTATION_FAMILY)
        );

        $trick4 = $this->newTrick(
            'Backflip',
            trim($trickDescription[3]),
            $this->getReference(UserFixtures::USER_ONE_REFERENCE),
            $this->getReference(FamilyFixtures::FLIP_FAMILY)
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
        UserFixtures::class,
        FamilyFixtures::class,
        ];
    }

    /**
     * @param $name
     * @param $description
     * @param $user
     * @param $family
     * @return Trick
     */
    public function newTrick($name, $description, $user, $family):Trick
    {
        $trick = new Trick();
        $trick->setName($name);
        $trick->setDescription($description);
        $trick->setCreated(new \DateTime());
        $trick->setUser($user);
        $trick->setFamily($family);

        $this->manager->persist($trick);

        return $trick;
    }
}
