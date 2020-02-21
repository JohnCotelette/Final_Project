<?php 

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RoutesBusinessTest extends WebTestCase {
    
    public function testBusinessShowOffer()
    {
        //The createClient() method returns a client
        $client = static::createClient();

        //Crawler object which can be used to select elements in the response,
        //click on links and submit forms.
        $crawler = $client->request('GET', '/business');

        //Use crawler for select a button
        $btn = $crawler->filter('h2 > a');

        //Get the textContent
        $btn = $btn->getNode(0)->textContent;

        //Click on link
        $crawler = $client->clickLink("$btn");

        //Success if clickLink works
        $this->assertResponseIsSuccessful();
    }

}