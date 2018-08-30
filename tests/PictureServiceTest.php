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

    private $file;
    private $image;
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

    public function testUpload()
    {
        $targetDirectory = getenv('TARGET_IMG_TRICKS_DIRECTORY');
        $objectManager   = $this->getContainer()->get(ObjectManager::class);

        $pictureService = new PictureService($targetDirectory, $objectManager);

        $photoPath    = '/Users/jonathan/Desktop/test.jpg';
        $photoPngPath = '/Users/jonathan/Desktop/Small-mario.png';

        //$photo[] = new UploadedFile($photoPath, 'photo.jpg', 'image/jpeg', null);
        $photo = new UploadedFile($this->image, 'Small-mario.png', 'image/png', null, true);

        //dd($this->image);

        $uploadedFile = $this->createMock(UploadedFile::class);
        $uploadedFile->expects($this->any())->method('move')->willReturn('kjlkj');


        $pictureService->upload($photo);
    }


    public function setUp()
    {
        $this->file = tempnam(sys_get_temp_dir(), 'upl');
        imagepng(imagecreatetruecolor(10, 10), $this->file); // create and write image/png to it
        $this->image = new UploadedFile(
            $this->file,
            'new_image.png'
        );


    }
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