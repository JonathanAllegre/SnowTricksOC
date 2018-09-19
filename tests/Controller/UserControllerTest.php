<?php
/**
 * Created by PhpStorm.
 * User: jonathan
 * Date: 19/09/2018
 * Time: 08:05
 */

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\BrowserKit\Cookie;

class UserControllerTest extends WebTestCase
{


    /**
     * TEST LOGIN USER
     *
     * php bin/phpUnit --filter
     * testLogin tests/Controller/UserControllerTest.php
     */
    public function testLogin()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertCount(1, $crawler->filter('h1'));
        $this->assertCount(3, $crawler->filter('input'));
        $this->assertEquals(1, $crawler->filter('html:contains("Se connecter ?")')->count());
    }

    /**
     * TEST LOGIN USER
     * ASSERT WHEN USER IS ALREADY LOGIN WE REDIRECT
     *
     * php bin/phpUnit --filter
     * testRegisterWithUserIsNoLoged tests/Controller/UserControllerTest.php
     */
    public function testRegisterWithUserIsNoLoged()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/register');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    /**
     * TEST LOGIN USER
     * ASSERT WHEN USER IS ALREADY LOGIN WE REDIRECT
     *
     * php bin/phpUnit --filter
     * testRegister tests/Controller/UserControllerTest.php
     */
    public function testRegisterWhenUserAlreadyLoged()
    {
        $client = static::createClient();
        $this->logIn($client);

        $crawler = $client->request('GET', '/register');
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
    }


    private function logIn(Client $client)
    {
        $session = $client->getContainer()->get('session');

        $firewallName = 'main';
        // if you don't define multiple connected firewalls, the context defaults to the firewall name
        // See https://symfony.com/doc/current/reference/configuration/security.html#firewall-context
        $firewallContext = 'main';

        // you may need to use a different token class depending on your application.
        // for example, when using Guard authentication you must instantiate PostAuthenticationGuardToken
        $token = new UsernamePasswordToken('admin', null, $firewallName, array('ROLE_ADMIN'));
        $session->set('_security_'.$firewallContext, serialize($token));
        $session->save();

        $cookie = new Cookie($session->getName(), $session->getId());
        $client->getCookieJar()->set($cookie);
    }


}