<?php 

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RoutesOffersTest extends WebTestCase {
    
    public function testRouteShowOffer()
    {
        //The createClient() method returns a client
        $client = static::createClient();

        //$client request the route
        $client->request('GET', '/offers');

        //$client click on link with string = 'En savoir plus'
        $client->clickLink('En savoir plus');

        //Success if logout works
        $this->assertResponseIsSuccessful();
    }

}