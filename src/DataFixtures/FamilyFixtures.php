<?php

namespace App\DataFixtures;

use App\Entity\Family;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class FamilyFixtures extends Fixture
{

    private $manager;

    const GRAB_FAMILY     = 'Grab';
    const FLIP_FAMILY     = 'Flip';
    const ROTATION_FAMILY = 'Rotation';

    public function __construct(ObjectManager $manager)
    {
        $this->manager = $manager;
    }
    public function load(ObjectManager $manager)
    {

        $grabFamily   = $this->newFamily('Grab');
        $rotateFamily = $this->newFamily('Rotation');
        $flipFamily   = $this->newFamily('Flip');

        $this->addReference(self::GRAB_FAMILY, $grabFamily);
        $this->addReference(self::ROTATION_FAMILY, $rotateFamily);
        $this->addReference(self::FLIP_FAMILY, $flipFamily);

        $manager->flush();
    }

    public function newFamily($name):Family
    {
        $family = new Family();
        $family->setTitle($name);

        $this->manager->persist($family);

        return $family;
    }


}
