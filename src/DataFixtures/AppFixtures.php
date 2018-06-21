<?php
/**
 * Created by PhpStorm.
 * User: jonathan
 * Date: 21/06/2018
 * Time: 14:17
 */

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{

    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setUsername('Jonathan');
        $user->setPlainPassword('simple');
        $user->setEmail('jonathan.allegre258@orange.fr');
        $user->setActive(1);
        $manager->persist($user);

        $manager->flush();
    }

}