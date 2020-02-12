<?php 

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RoutesOffersTest extends WebTestCase {
    
    public function testRouteShowOffer()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/offers');

        $crawler = $client->clickLink('En savoir plus');

        
        $this->assertResponseIsSuccessful();
    }

}