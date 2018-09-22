<?php
/**
 * Created by PhpStorm.
 * User: jonathan
 * Date: 20/09/2018
 * Time: 08:35
 */

namespace App\Tests\Controller;

use App\Entity\Trick;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\BrowserKit\Cookie;

class TrickControllerTest extends WebTestCase
{

    /**
     * php bin/phpunit --filter
     * testDetail  tests/Controller/TrickControllerTest.php
     */
    public function testDetail()
    {
        $client = static::createClient();

        $trick = $this->getContainer()->get('doctrine')->getRepository(Trick::class)->findOneByName('Stalefish');

        // TEST WITH KNOWN ID
        $crawler = $client->request('GET', '/trick/detail/' . $trick->getId() . '/' . $trick->getSlug());
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals(1, $crawler->filter('h1')->count());
        $this->assertEquals(1, $crawler->filter('html:contains("Stalefish")')->count());

        // TEST AVEC UNKNOW ID
        $crawler = $client->request('GET', '/trick/detail/unknownId');
        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }

    public function testAdd()
    {
        $client = static::createClient();

        //TEST WITH NO LOGIN
        $crawler = $client->request('GET', '/trick/add');
        $this->assertEquals(302, $client->getResponse()->getStatusCode());

        //TEST WITH LOGIN OK
        $this->logIn($client);

        $crawler = $client->request('GET', '/trick/add');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals(1, $crawler->filter('html:contains("Ajouter une figure")')->count());
    }

    /**
     * TEST UPDATE
     */
    public function testUpdate()
    {
        $client = static::createClient();

        // TEST UPDATE WITHOUT LOGIN
        // MUST RETOURN 302
        $trick = $this->getContainer()->get('doctrine')->getRepository(Trick::class)->findOneByName('Stalefish');

        $crawler = $client->request('GET', '/trick/update/'.$trick->getId());
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        $crawler = $client->followRedirect();
        $this->assertEquals(1, $crawler->filter('html:contains("Se connecter ?")')->count());

        // TEST UPDATE WITH LOGIN OK
        // MUST RETOURN 200
        $this->logIn($client);
        $crawler = $client->request('GET', '/trick/update/'.$trick->getId());
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals(1, $crawler->filter('html:contains("Modifier l\'image à la une")')->count());

    }


    protected function logIn(Client $client)
    {
        $session = $client->getContainer()->get('session');

        $firewallName = 'main';
        // if you don't define multiple connected firewalls, the context defaults to the firewall name
        // See https://symfony.com/doc/current/reference/configuration/security.html#firewall-context
        $firewallContext = 'main';

        // you may need to use a different token class depending on your application.
        // for example, when using Guard authentication you must instantiate PostAuthenticationGuardToken
        $token = new UsernamePasswordToken('admin', null, $firewallName, array('ROLE_USER'));
        $session->set('_security_'.$firewallContext, serialize($token));
        $session->save();

        $cookie = new Cookie($session->getName(), $session->getId());
        $client->getCookieJar()->set($cookie);
    }


    protected function getContainer()
    {
        self::bootKernel();
        // returns the real and unchanged service container
        $container = self::$kernel->getContainer();
        // gets the special container that allows fetching private services
        $container = self::$container;

        return $container;
    }
}