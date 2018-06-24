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
use App\Entity\Video;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{

    private $encoder;
    private $manager;

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
        $user = $this->newUser('jonathan', 'simple', 'jonathan.allegre258@orange.fr');

        $trick1  = $this->newTrick('Mute', 'C le mute', $user);
        $this->newPicture('test.jpeg', $trick1);
        $this->newPicture('test2.jpeg', $trick1);
        $this->newVideo('youtube/khkhkh', $trick1);
        $this->newVideo('youtube/khkhk', $trick1);
        $this->newVideo('youtube/khkh', $trick1);
        $this->newVideo('youtube/khkkjhlkj', $trick1);


        $trick2 = $this->newTrick('Stalefish', 'C le Stalefish', $user);

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

        $this->manager->persist($user);

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

        $this->manager->persist($trick);

        return $trick;
    }

    /**
     * @param $name
     * @param $trick
     * @return Picture
     */
    public function newPicture($name, $trick):Picture
    {
        $picture = new Picture();
        $picture->setName($name);
        $picture->setCreated(new \DateTime());
        $picture->setTrick($trick);

        $this->manager->persist($picture);

        return $picture;
    }

    /**
     * @param $url
     * @param $trick
     * @return Video
     */
    public function newVideo($url, $trick):Video
    {
        $video = new Video();
        $video->setUrl($url);
        $video->setCreated(new \DateTime());
        $video->setTrick($trick);

        $this->manager->persist($video);

        return $video;
    }


}
