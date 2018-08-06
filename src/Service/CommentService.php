<?php
/**
 * Created by PhpStorm.
 * User: jonathan
 * Date: 10/07/2018
 * Time: 21:33
 */

namespace App\Service;

use App\Entity\Comment;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * Class CommentService
 * @package App\Service
 */
class CommentService
{
    private $doctrine;

    /**
     * CommentService constructor.
     * @param $doctrine
     */
    public function __construct(RegistryInterface $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    /**
     * @param Comment $comment
     */
    public function add(Comment $comment)
    {
        $manager = $this->doctrine->getManager();
        $manager->persist($comment);
        $manager->flush();
    }
}
