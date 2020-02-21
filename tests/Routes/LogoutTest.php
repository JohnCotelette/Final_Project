<?php 

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RoutesLogoutTest extends WebTestCase {

    function testRouteLogout()
    {
        //The createClient() method returns a client
        $client = static::createClient();

        //$client request the route
        $client->request('GET', '/logout');

        //Redirection
        $this->assertResponseRedirects("");

        //$client follow the redirection
        $client->followRedirect();

        //Success if follow it's work
        $this->assertResponseIsSuccessful();
    }

}