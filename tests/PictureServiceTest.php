<?php
/**
 * Created by PhpStorm.
 * User: jonathan
 * Date: 29/08/2018
 * Time: 07:40
 */

namespace App\Tests;

use App\Entity\Trick;
use App\Service\PictureService;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class PictureServiceTest extends KernelTestCase
{

    /**
     * TEST GET TARGET DIRECTORY
     * MUST RETURN "public/img.tricks"
     */
    public function testGetTargetDirectory()
    {
        $targetDirectory = getenv('TARGET_IMG_TRICKS_DIRECTORY');
        $objectManager   = $this->getContainer()->get(ObjectManager::class);


        $pictureService = new PictureService($targetDirectory, $objectManager);

        $this->assertEquals('public/img/tricks', $pictureService->getTargetDirectory());
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