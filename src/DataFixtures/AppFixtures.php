<?php
/**
 * Created by PhpStorm.
 * User: jonathan
 * Date: 21/06/2018
 * Time: 14:17
 */

namespace App\DataFixtures;

use App\Entity\Comment;
use App\Entity\Family;
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

        $video            = file(__DIR__.'/video.txt');
        $picture          = file(__DIR__.'/picture.txt');
        $trickDescription = file(__DIR__.'/trickDescription.txt');



        $grabFamily   = $this->newFamily('Grab');
        $rotateFamily = $this->newFamily('Rotation');
        $flipFamily   = $this->newFamily('Flip');


        $user1 = $this->newUser('jonathan', 'simple', 'jonathan.allegre258@orange.fr');

        $trick1         = $this->newTrick('Mute', $trickDescription[0], $user1, $grabFamily);
        $listingPicture = $this->newPicture($picture[0], $trick1);
        $this->newPicture($picture[1], $trick1);
        $this->newVideo($video[0], $trick1);
        $this->newVideo($video[1], $trick1);
        $this->newComment($trick1, $user1, 'Trop cool le mute et facile en plus');
        $trick1->setListingPicture($listingPicture);

        $trick2         = $this->newTrick('Stalefish', $trickDescription[1], $user1, $grabFamily);
        $listingPicture = $this->newPicture($picture[2], $trick2);
        $this->newPicture($picture[3], $trick2);
        $this->newVideo($video[2], $trick2);
        $this->newComment($trick2, $user1, 'Un bon stalefish, et au lit !');
        $trick2->setListingPicture($listingPicture);

        $trick3         = $this->newTrick('180', $trickDescription[2], $user1, $rotateFamily);
        $listingPicture = $this->newPicture($picture[4], $trick3);
        $this->newPicture($picture[5], $trick3);
        $trick2->setListingPicture($listingPicture);

        $trick4         = $this->newTrick('Backflip', $trickDescription[3], $user1, $flipFamily);
        $this->newVideo($video[3], $trick4);
        $listingPicture = $this->newPicture($picture[6], $trick4);
        $trick4->setListingPicture($listingPicture);
        $this->newPicture($picture[7], $trick4);


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

    public function newFamily($name):Family
    {
        $family = new Family();
        $family->setTitle($name);

        $this->manager->persist($family);

        return $family;
    }

    public function newComment(Trick $trick, User $user, $content):Comment
    {
        $comment = new Comment();
        $comment->setTrick($trick);
        $comment->setCreated(new \DateTime());
        $comment->setUser($user);
        $comment->setContent($content);

        $this->manager->persist($comment);

        return $comment;
    }
}
