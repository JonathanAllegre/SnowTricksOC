<?php

namespace App\DataFixtures;

use App\Entity\Family;
use App\Entity\Trick;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class TrickFixtures extends Fixture implements DependentFixtureInterface
{

    private $manager;

    const TRICK_MUTE      = 'Mute';
    const TRICK_STALEFISH = 'Stalefish';
    const TRICK_180       = '180';
    const TRICK_BACKFLIP  = 'Backflip';

    public function __construct(ObjectManager $manager)
    {
        $this->manager = $manager;
    }

    public function load(ObjectManager $manager)
    {
        $trickDescription = file(__DIR__.'/trickDescription.txt');


        $trickMute = $this->newTrick(
            'Mute',
            trim($trickDescription[0]),
            $this->getReference(UserFixtures::USER_ONE_REFERENCE),
            $this->getReference(FamilyFixtures::GRAB_FAMILY)
        );

        $trickStalefish = $this->newTrick(
            'Stalefish',
            trim($trickDescription[1]),
            $this->getReference(UserFixtures::USER_ONE_REFERENCE),
            $this->getReference(FamilyFixtures::GRAB_FAMILY)
        );

        $trick180 = $this->newTrick(
            '180',
            trim($trickDescription[2]),
            $this->getReference(UserFixtures::USER_ONE_REFERENCE),
            $this->getReference(FamilyFixtures::ROTATION_FAMILY)
        );

        $trickBackflip = $this->newTrick(
            'Backflip',
            trim($trickDescription[3]),
            $this->getReference(UserFixtures::USER_ONE_REFERENCE),
            $this->getReference(FamilyFixtures::FLIP_FAMILY)
        );

        $this->addReference(self::TRICK_MUTE, $trickMute);
        $this->addReference(self::TRICK_STALEFISH, $trickStalefish);
        $this->addReference(self::TRICK_180, $trick180);
        $this->addReference(self::TRICK_BACKFLIP, $trickBackflip);

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
    public function newTrick($name, $description, User $user, Family $family):Trick
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