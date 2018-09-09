<?php
/**
 * Created by PhpStorm.
 * User: jonathan
 * Date: 08/09/2018
 * Time: 21:17
 */

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HomeControllerTest extends WebTestCase
{

    /**
     * TEST REGISTERUSER
     *
     * php bin/phpUnit --filter
     * testIndex tests/Controller/HomeControllerTest.php
     */
    public function testIndex()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals(1, $crawler->filter('12'));

    }

}