<?php
/**
 * Created by PhpStorm.
 * User: jonathan
 * Date: 21/06/2018
 * Time: 14:17
 */

namespace App\DataFixtures;

use App\Entity\Trick;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{

    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setUsername('Jonathan');
        $user->setPassword($this->encoder->encodePassword($user, 'simple'));
        $user->setEmail('jonathan.allegre258@orange.fr');
        $user->setActive(1);
        $manager->persist($user);

        $trick = new Trick();
        $trick->setName('mute');
        $trick->setDescription('blabla');
        $trick->setCreated(new \DateTime());
        $trick->setUser($user);
        $manager->persist($trick);

        $manager->flush();
    }

}