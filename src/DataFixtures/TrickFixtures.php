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

    const TRICK_MUTE          = 'Mute';
    const TRICK_STALEFISH     = 'Stalefish';
    const TRICK_180           = '180';
    const TRICK_BACKFLIP      = 'Backflip';
    const TRICK_INDY          = 'Indy';
    const TRICK_TAIL_GRAB     = 'Tail Grab';
    const TRICK_METHOD_GRAB   = 'Method Grab';
    const TRICK_NOSE_GRAB     = 'Nose Grab';
    const TRICK_DOUBLE_CORK   = 'FS Double Cork';
    const TRICK_HANDPLANT     = 'Handplant';


    public function __construct(ObjectManager $manager)
    {
        $this->manager = $manager;
    }

    public function load(ObjectManager $manager)
    {
        $trickDescription = file(__DIR__.'/contentFiles/trickDescription.txt');
        $lorem            = file_get_contents(__DIR__.'/contentFiles/lorem.txt');


        $trickMute = $this->newTrick(
            'Mute',
            trim($trickDescription[0]),
            $lorem,
            $this->getReference(UserFixtures::USER_ONE_REFERENCE),
            $this->getReference(FamilyFixtures::GRAB_FAMILY)
        );

        $trickStalefish = $this->newTrick(
            'Stalefish',
            trim($trickDescription[1]),
            $lorem,
            $this->getReference(UserFixtures::USER_ONE_REFERENCE),
            $this->getReference(FamilyFixtures::GRAB_FAMILY),
            true
        );

        $trick180 = $this->newTrick(
            '180',
            trim($trickDescription[2]),
            $lorem,
            $this->getReference(UserFixtures::USER_ONE_REFERENCE),
            $this->getReference(FamilyFixtures::ROTATION_FAMILY)
        );

        $trickBackflip = $this->newTrick(
            'Backflip',
            trim($trickDescription[3]),
            $lorem,
            $this->getReference(UserFixtures::USER_ONE_REFERENCE),
            $this->getReference(FamilyFixtures::FLIP_FAMILY)
        );

        $trickIndy = $this->newTrick(
            'Indy',
            trim($trickDescription[4]),
            $lorem,
            $this->getReference(UserFixtures::USER_ONE_REFERENCE),
            $this->getReference(FamilyFixtures::GRAB_FAMILY)
        );

        $trickTailGrab = $this->newTrick(
            'Tail Grab',
            trim($trickDescription[5]),
            $lorem,
            $this->getReference(UserFixtures::USER_ONE_REFERENCE),
            $this->getReference(FamilyFixtures::GRAB_FAMILY)
        );

        $trickMethod = $this->newTrick(
            'Method Grab',
            trim($trickDescription[6]),
            $lorem,
            $this->getReference(UserFixtures::USER_ONE_REFERENCE),
            $this->getReference(FamilyFixtures::GRAB_FAMILY)
        );

        $trickNoseGrab = $this->newTrick(
            'Nose Grab',
            trim($trickDescription[7]),
            $lorem,
            $this->getReference(UserFixtures::USER_ONE_REFERENCE),
            $this->getReference(FamilyFixtures::GRAB_FAMILY)
        );

        $trickDoubleCork = $this->newTrick(
            'FS Double Cork 1080°',
            trim($trickDescription[8]),
            $lorem,
            $this->getReference(UserFixtures::USER_ONE_REFERENCE),
            $this->getReference(FamilyFixtures::FLIP_FAMILY)
        );

        $trickHandplant = $this->newTrick(
            'Handplant',
            trim($trickDescription[9]),
            $lorem,
            $this->getReference(UserFixtures::USER_ONE_REFERENCE),
            $this->getReference(FamilyFixtures::GRAB_FAMILY)
        );



        $this->addReference(self::TRICK_MUTE, $trickMute);
        $this->addReference(self::TRICK_STALEFISH, $trickStalefish);
        $this->addReference(self::TRICK_180, $trick180);
        $this->addReference(self::TRICK_BACKFLIP, $trickBackflip);
        $this->addReference(self::TRICK_INDY, $trickIndy);
        $this->addReference(self::TRICK_TAIL_GRAB, $trickTailGrab);
        $this->addReference(self::TRICK_METHOD_GRAB, $trickMethod);
        $this->addReference(self::TRICK_NOSE_GRAB, $trickNoseGrab);
        $this->addReference(self::TRICK_DOUBLE_CORK, $trickDoubleCork);
        $this->addReference(self::TRICK_HANDPLANT, $trickHandplant);

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
    public function newTrick($name, $description, $lorem, User $user, Family $family, $updatedDate = false):Trick
    {
        $description = "<b>".$description."</b>\n\n".$lorem;

        $trick = new Trick();
        $trick->setName($name);
        $trick->setDescription($description);
        $trick->setCreated(new \DateTime());
        $trick->setUser($user);
        $trick->setFamily($family);
        if (true === $updatedDate) {
            $trick->setUpdated(new \DateTime);
        }

        $this->manager->persist($trick);

        return $trick;
    }
}
