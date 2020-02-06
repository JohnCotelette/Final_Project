<?php 

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RoutesLoginTest extends WebTestCase {
    
    function testRouteLogin()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');
        $buttonCrawlerNode = $crawler->selectButton('submit');
        $form = $buttonCrawlerNode->form();
        $form = $buttonCrawlerNode->form([
            'email' => 'candidate0@hotmail.fr',
            'password' => '12345678',
        ]);
        
        $client->submit($form);
        $this->assertResponseRedirects('/offers');
        $client->followRedirect();
        $this->assertResponseIsSuccessful();

    }

    public function testFieldLoginForm()
    {
        //The createClient() method returns a client
        $client = static::createClient();

        //Crawler object which can be used to select elements in the response,
        //click on links and submit forms.
        $crawler = $client->request('GET', '/login');

        //Select the button with ID name
        $buttonCrawlerNode = $crawler->selectButton('submit');

        //Get form for override some form values and submit the corresponding form
        $form = $buttonCrawlerNode->form([
            'email' => 'candidate0.fr',
            'password' => 1354
        ]);

        //Submit form
        $crawler = $client->submit($form);
        //Get errors 
        $error = $crawler->filter('#form');
        
        //Get string
        $error =  $error->getNode(0)->textContent;

        $this->assertEquals($error, 'Les identifiants saisis sont incorrects.');
    }
}