<?php
/**
 * Created by PhpStorm.
 * User: jonathan
 * Date: 30/06/2018
 * Time: 10:44
 */

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    private $encoder;
    private $manager;

    public const USER_ONE_REFERENCE = 'jonathan';

    public function __construct(UserPasswordEncoderInterface $encoder, ObjectManager $manager)
    {
        $this->encoder = $encoder;
        $this->manager = $manager;
    }

    /**
     * @param ObjectManager $manager
     * @throws \Exception
     */
    public function load(ObjectManager $manager)
    {


        $userOne = $this->newUser('jonathan', 'simple', 'jonathan.allegre258@orange.fr');
        $manager -> persist($userOne);
        $manager -> flush();

        // other fixtures can get this object using the UserFixtures::ADMIN_USER_REFERENCE constant
        $this -> addReference(self::USER_ONE_REFERENCE, $userOne);
    }

    /**
     * @param $name
     * @param $pass
     * @param $mail
     * @return User
     * @throws \Exception
     */
    public function newUser($name, $pass, $mail):User
    {
        $user = new User();
        $user->setUsername($name);
        $user->setPassword($this->encoder->encodePassword($user, $pass));
        $user->setEmail($mail);
        $user->setActive(1);
        $user->setUserPicture('default.svg');

        $this->manager->persist($user);

        return $user;
    }
}
