<?php
/**
 * Created by PhpStorm.
 * User: jonathan
 * Date: 10/07/2018
 * Time: 21:33
 */

namespace App\Service;

use App\Entity\Comment;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * Class CommentService
 * @package App\Service
 */
class CommentService
{
    private $manager;

    /**
     * CommentService constructor.
     * @param $doctrine
     */
    public function __construct(ObjectManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @param Comment $comment
     * @return bool
     */
    public function add(Comment $comment)
    {
        $this->manager->persist($comment);
        if ($this->manager->flush()) {
            return true;
        }

        return false;
    }
}
