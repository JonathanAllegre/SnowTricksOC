<?php
/**
 * Created by PhpStorm.
 * User: jonathan
 * Date: 21/06/2018
 * Time: 14:17
 */

namespace App\DataFixtures;

use App\Entity\Picture;
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

    /**
     * @param ObjectManager $manager
     * @throws \Exception
     */
    public function load(ObjectManager $manager)
    {
        $user = $this->newUser('jonathan', 'simple', 'jonathan.allegre258@orange.fr');
        $manager->persist($user);


        $trick  = $this->newTrick('Mute', 'C le mute', $user);
        $picture = $this->newPicture('test.jpeg', $trick);
        $manager->persist($picture);
        $picture = $this->newPicture('test2.jpeg', $trick);
        $manager->persist($picture);
        $manager->persist($trick);


        $trick = $this->newTrick('Stalefish', 'C le Stalefish', $user);
        $manager->persist($trick);


        $manager->flush();
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

        return $user;
    }

    /**
     * @param $name
     * @param $description
     * @param User $user
     * @return Trick
     */
    public function newTrick($name, $description, User $user):Trick
    {
        $trick = new Trick();
        $trick->setName($name);
        $trick->setDescription($description);
        $trick->setCreated(new \DateTime());
        $trick->setUser($user);

        return $trick;
    }

    public function newPicture($name, $trick):Picture
    {
        $picture = new Picture();
        $picture->setName($name);
        $picture->setCreated(new \DateTime());
        $picture->setTrick($trick);

        return $picture;

    }
}
