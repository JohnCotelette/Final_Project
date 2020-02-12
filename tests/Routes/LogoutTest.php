<?php 

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RoutesLogoutTest extends WebTestCase {
    

    function testRouteLogout()
    {
        $client = static::createClient();
        $client->request('GET', '/logout');
        $this->assertResponseRedirects("");
        $client->followRedirect();
        $this->assertResponseIsSuccessful();
    }


}