<?php
/**
 * Created by PhpStorm.
 * User: jonathan
 * Date: 04/09/2018
 * Time: 07:51
 */

namespace App\Tests;

use App\Entity\User;
use App\Service\MailerService;
use App\Service\UserService;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserServiceTest extends KernelTestCase
{

    /**
     * TEST ACCOUNT CONFIRMATION
     *
     * php bin/phpUnit --filter
     * testAccuntConfirmation tests/UserServiceTest.php
     */
    public function testAccuntConfirmation()
    {

        $registryInterface   = $this->getContainer()->get(ObjectManager::class);
        $mailerService       = $this->getContainer()->get(MailerService::class);
        $userPasswordEncoder = $this->getContainer()->get(UserPasswordEncoderInterface::class);
        $sessionInterface    = $this->getContainer()->get(SessionInterface::class);

        $userService = new UserService($registryInterface, $mailerService, $userPasswordEncoder, $sessionInterface);

        // ASSERT A USER ALLREADY ACTIVE RETURN ALLREADY_ACTIVE const.
        $user = (new User())->setActive(1);
        $this->assertEquals("Votre compte est déjà actif.", $userService->accuntConfirmation($user));
    }

    /**
     * TEST FORGOT PASSWORD
     *
     * php bin/phpUnit --filter
     * testForgotPassword tests/UserServiceTest.php
     */
    public function testForgotPassword()
    {

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