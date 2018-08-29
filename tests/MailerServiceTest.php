<?php
/**
 * Created by PhpStorm.
 * User: jonathan
 * Date: 28/08/2018
 * Time: 07:26
 */

namespace App\Tests;

use App\Entity\User;
use App\Service\MailerService;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class MailerServiceTest extends KernelTestCase
{

    /**
     * EST SEND REGISTER CONFIRMATION WITH NO ERROR IN SEND MAIL
     * MUST RETURN TRUE
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function testSendRegisterConfirmationWithSwiftReturnTrue()
    {

        $twig  = $this->getContainer()->get(\Twig_Environment::class);
        $swift = $this->mockSwiftMailerSend(true);

        $mailerService = new MailerService($swift, $twig);

        $user = new User();

        $this->assertTrue($mailerService->sendRegisterConfirmation($user));
    }

    /**
     * TEST SEND REGISTER CONFIRMATION WITH ERROR IN SEND MAIL
     * MUST RETURN FALSE
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function testSendRegisterConfirmationWithSwiftReturnFalse()
    {

        $twig  = $this->getContainer()->get(\Twig_Environment::class);
        $swift = $this->mockSwiftMailerSend(false);

        $mailerService = new MailerService($swift, $twig);

        $user = (new User())
            ->setUsername('testUser')
            ->setEmail('User@user.test');


        $this->assertfalse($mailerService->sendRegisterConfirmation($user));
    }

    /**
     * TEST SEND RESET PASS  WITH NO ERROR IN SEND MAIL
     * MUST RETURN TRUE
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function testSendResetPass()
    {
        $user = (new User())
            ->setUsername('testUser')
            ->setEmail('User@user.test');


        $twig  = $this->getContainer()->get(\Twig_Environment::class);
        $swift = $this->mockSwiftMailerSend(true);

        $mailerService = new MailerService($swift, $twig);

        $this->assertTrue($mailerService->sendResetPass($user));
    }

    /**
     * TEST SEND RESET PASS  WITH ERROR IN SEND MAIL
     * MUST RETURN FALSE
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function testSendResetPassWithErrorSendMail()
    {
        $user = (new User())
            ->setUsername('testUser')
            ->setEmail('User@user.test');


        $twig  = $this->getContainer()->get(\Twig_Environment::class);
        $swift = $this->mockSwiftMailerSend(false);

        $mailerService = new MailerService($swift, $twig);

        $this->assertFalse($mailerService->sendResetPass($user));
    }

    private function mockSwiftMailerSend($value)
    {

        $swift = $this->createMock(\Swift_Mailer::class);
        $swift->expects($this->any())->method('send')->willReturn($value);

        return $swift;
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