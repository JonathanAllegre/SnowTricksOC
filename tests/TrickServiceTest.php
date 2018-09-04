<?php
/**
 * Created by PhpStorm.
 * User: jonathan
 * Date: 04/09/2018
 * Time: 07:14
 */

namespace App\Tests;

use App\Entity\Picture;
use App\Entity\Trick;
use App\Service\PictureService;
use App\Service\TrickService;
use App\Service\VideoService;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class TrickServiceTest extends KernelTestCase
{

    /**
     * php bin/phpUnit --filter
     * testTrickHasPicture tests/TrickServiceTest.php
     *
     * TEST TRICK HAS PICTURE
     */
    public function testTrickHasPicture()
    {

        $pictureService = $this->getContainer()->get(PictureService::class);
        $videoService   = $this->getContainer()->get(VideoService::class);

        // TEST IF TRICK HAS NO IMAGE
        // MUST RETURN FALSE
        $trickService = new TrickService($pictureService, $videoService);
        $trick        = (new Trick())->setPictures([]);

        $this->assertFalse($trickService->trickHasPicture($trick));

        // TEST IF TRICK HAS IMAGE
        // MUST RETURN TRUE
        $pictureServiceMock = $this->createMock(PictureService::class);
        $pictureServiceMock->expects($this->any())->method('savePicture')->willReturn(true);

        $trickService = new TrickService($pictureServiceMock, $videoService);
        $trick        = (new Trick())->setPictures(['test' => "test"]);

        $this->assertTrue($trickService->trickHasPicture($trick));

        // TEST IF TRICKASPICTURE WITH OHER ENTITY IN ARGUMENT
        // MUST RETURN EXCEPTION
        $this->expectException(\TypeError::class);

        $trickService->trickHasPicture(new Picture());
    }

    /**
     * @return \Symfony\Component\DependencyInjection\ContainerInterface
     */
    private function getContainer()
    {
        self::bootKernel();
        // returns the real and unchanged service container
        $container = self::$kernel->getContainer();
        // gets the special container that allows fetching private services
        $container = self::$container;

        return $container;
    }
}