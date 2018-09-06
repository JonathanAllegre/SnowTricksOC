<?php
/**
 * Created by PhpStorm.
 * User: jonathan
 * Date: 04/09/2018
 * Time: 07:51
 */

namespace App\Tests;

use App\Entity\User;
use App\Repository\UserRepository;
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
        $registryInterface   = $this->getContainer()->get(ObjectManager::class);
        $mailerService       = $this->getContainer()->get(MailerService::class);
        $userPasswordEncoder = $this->getContainer()->get(UserPasswordEncoderInterface::class);
        $sessionInterface    = $this->getContainer()->get(SessionInterface::class);

        $userService = new UserService($registryInterface, $mailerService, $userPasswordEncoder, $sessionInterface);

        // TEST IF USER FOUND & MAILER TRUE
        // MUST RETURN TRUE

        // MOCK REPO & MANAGER
        $user = (new User())->setEmail('test@test.com');
        $userRepo = $this->createMock(UserRepository::class);
        $userRepo->expects($this->any())
            ->method('findOneBy')
            ->willReturn($user);

        $objectManager = $this->createMock(ObjectManager::class);
        $objectManager->expects($this->any())
            ->method('getRepository')
            ->willReturn($userRepo);

        // MOCK MAILER
        $mailer = $this->createMock(MailerService::class);
        $mailer->expects($this->any())
            ->method('sendResetPass')
            ->willReturn(true);

        $form = ['username' => 'test'];
        $userService = new UserService($objectManager, $mailer, $userPasswordEncoder, $sessionInterface);
        $this->assertTrue($userService->forgotPassword($form));

        // TEST IF USER NOT FOUND & MAILER TRUE
        // MUST RETURN "Aucun utilsateur trouvé"

        // MOCK REPO & MANAGER
        $userRepo = $this->createMock(UserRepository::class);
        $userRepo->expects($this->any())
            ->method('findOneBy')
            ->willReturn(false);

        $objectManager = $this->createMock(ObjectManager::class);
        $objectManager->expects($this->any())
            ->method('getRepository')
            ->willReturn($userRepo);

        $userService = new UserService($objectManager, $mailer, $userPasswordEncoder, $sessionInterface);
        $this->assertEquals("Aucun utilisateur trouvé", $userService->forgotPassword($form));

        // TEST IF USER FOUND & MAILER FALSE
        // MUST RETURN "Une erreur est survenue lors de l'envoie du mail"

        // MOCK REPO & MANAGER
        $userRepo = $this->createMock(UserRepository::class);
        $userRepo->expects($this->any())
            ->method('findOneBy')
            ->willReturn($user);

        $objectManager = $this->createMock(ObjectManager::class);
        $objectManager->expects($this->any())
            ->method('getRepository')
            ->willReturn($userRepo);

        // MOCK MAILER
        $mailer = $this->createMock(MailerService::class);
        $mailer->expects($this->any())
            ->method('sendResetPass')
            ->willReturn(false);

        $userService = new UserService($objectManager, $mailer, $userPasswordEncoder, $sessionInterface);
        $this->assertEquals("Une erreur est survenue lors de l'envoie du mail", $userService->forgotPassword($form));
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