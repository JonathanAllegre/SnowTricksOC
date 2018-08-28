<?php
/**
 * Created by PhpStorm.
 * User: jonathan
 * Date: 27/08/2018
 * Time: 07:57
 */

namespace App\Tests;

use App\Entity\Comment;
use App\Service\CommentService;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Bundle\TwigBundle\Tests\TestCase;

class CommentServiceTest extends TestCase
{

    public function testAdd()
    {
        $doctrine = new Registry();
        $comment = new CommentService();

        $this->assertEquals(true, $comment->add('ùmlkùmlk') );
    }

}