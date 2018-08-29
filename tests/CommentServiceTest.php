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
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Bundle\TwigBundle\Tests\TestCase;

class CommentServiceTest extends TestCase
{
    public function testAdd()
    {
        $manager = $this->createMock(ObjectManager::class);

        $manager->expects($this->any())->method('flush')->willReturn(true);

        $commentSer = new CommentService($manager);

        $comment = (new Comment())->setTrick(1)->setUser(1)->setCreated(new \DateTime());
        $this->assertTrue($commentSer->add($comment));
    }
}
