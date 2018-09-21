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

        $crawler = $client->request('GET', '/trick/detail/' . $trick->getId() . '/' . $trick->getSlug());

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals(1, $crawler->filter('h1')->count());
        $this->assertEquals(1, $crawler->filter('html:contains("Stalefish")')->count());

    }

    public function getContainer()
    {
        self::bootKernel();
        // returns the real and unchanged service container
        $container = self::$kernel->getContainer();
        // gets the special container that allows fetching private services
        $container = self::$container;

        return $container;
    }
}