<?php

namespace App\DataFixtures;

use App\Entity\Comment;
use App\Entity\Trick;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class CommentFixtures extends Fixture implements DependentFixtureInterface
{
    private $manager;

    public function __construct(ObjectManager $manager)
    {
        $this->manager = $manager;
    }


    public function load(ObjectManager $manager)
    {
        $this->newComment(
            $this->getReference(TrickFixtures::TRICK_MUTE),
            $this->getReference(UserFixtures::USER_ONE_REFERENCE),
            'Trop cool le mute et facile en plus'
        );

        $this->newComment(
            $this->getReference(TrickFixtures::TRICK_STALEFISH),
            $this->getReference(UserFixtures::USER_ONE_REFERENCE),
            'Un bon stalefish, et au lit !'
        );

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
           TrickFixtures::class,
           UserFixtures::class,
        ];
    }


    /**
     * @param $trick
     * @param $user
     * @param $content
     * @return Comment
     */
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
