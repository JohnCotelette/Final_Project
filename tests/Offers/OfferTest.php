<?php 

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RoutesOffersTest extends WebTestCase {
    
    public function testRouteShowOffer()
    {
        $client = static::createClient();
        $client->request('GET', '/offers');
        $client->clickLink('En savoir plus');
        $this->assertResponseIsSuccessful();
    }

}