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
        $grabFamily   = $this->newFamily('Grab');
        $rotateFamily = $this->newFamily('Rotation');
        $flipFamily   = $this->newFamily('Flip');


        $user1 = $this->newUser('jonathan', 'simple', 'jonathan.allegre258@orange.fr');

        $trick1         = $this->newTrick('Mute', 'saisie de la carre frontside de la planche entre les deux pieds avec la main avant ;', $user1, $grabFamily);
        $listingPicture = $this->newPicture('https://upload.wikimedia.org/wikipedia/commons/d/df/Aviano_snowboarder.JPG', $trick1);
        $this->newPicture('https://www.summitdaily.com/wp-content/uploads/2016/12/USGrandPrixPhotos-sdn-121816-12-1240x859.jpg', $trick1);
        $this->newVideo('<iframe width="560" height="315" src="https://www.youtube.com/embed/hih9jIzOoRg" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>', $trick1);
        $this->newVideo('<iframe width="560" height="315" src="https://www.youtube.com/embed/CA5bURVJ5zk" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>', $trick1);
        $this->newComment($trick1, $user1, 'Trop cool le mute et facile en plus');
        $trick1->setListingPicture($listingPicture);


        $trick2         = $this->newTrick('Stalefish', 'saisie de la carre backside de la planche entre les deux pieds avec la main arrière ;', $user1, $grabFamily);
        $listingPicture = $this->newPicture('https://c1.staticflickr.com/4/3623/3349766374_4d5a21fa06_b.jpg', $trick2);
        $this->newPicture('https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcR77FBcNmHo6akKUJL8dfQBvrKkiPiVcIyhDx2mS2TqA8kRrayJ', $trick2);
        $this->newVideo('<iframe width="560" height="315" src="https://www.youtube.com/embed/6z6KBAbM0MY" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>', $trick2);
        $this->newComment($trick2, $user1, 'Un bon stalefish, et au lit !');
        $trick2->setListingPicture($listingPicture);


        $trick3         = $this->newTrick('180', 'désigne un demi-tour, soit 180 degrés d\'angle ;', $user1, $rotateFamily);
        $listingPicture = $this->newPicture('https://c1.staticflickr.com/3/2075/2319855559_2887938f5b_b.jpg', $trick3);
        $this->newPicture('https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcR77FBcNmHo6akKUJL8dfQBvrKkiPiVcIyhDx2mS2TqA8kRrayJ', $trick3);
        $trick2->setListingPicture($listingPicture);

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
