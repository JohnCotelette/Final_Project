<?php 

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RoutesBusinessTest extends WebTestCase {
    
    public function testBusinessShowOffer()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/business');
        $btn = $crawler->filter('h2 > a');
        $btn = $btn->getNode(0)->textContent;
        $crawler = $client->clickLink("$btn");
        $this->assertResponseIsSuccessful();
    }

}