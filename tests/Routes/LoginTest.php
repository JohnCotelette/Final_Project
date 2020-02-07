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

        $form = $buttonCrawlerNode->form();
        $form = $buttonCrawlerNode->form([
            'email' => 'candidate0@hotmail.fr',
            'password' => '',
        ]);
        
        //Submit form
        $client->submit($form);
        $crawler = $client->followRedirect();

        //Get errors 
        $error = $crawler->filter('#error');
   
        //Get string
        $error =  $error->getNode(0)->textContent;

        $this->assertEquals($error, 'Les identifiants saisis sont incorrects.');
    }
}