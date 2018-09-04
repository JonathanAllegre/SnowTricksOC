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

        $this->assertTrue($commentSer->add(new Comment()));

        $this->expectException(\TypeError::class);


        $commentSer->add(new \StdClass());
    }


}
