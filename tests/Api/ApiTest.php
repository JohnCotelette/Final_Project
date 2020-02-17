<?php 

namespace App\Tests\Controller;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ApiTest extends WebTestCase {
       
    public function testOfferApi()
    {
        $client = static::createClient();
        $client->request('GET', '/api/offers');
        $this->assertResponseIsSuccessful();
    }

    public function testSingleOfferApi()
    {
        $client = static::createClient();
        $client->request('GET', '/api/offers');
        $response = $client->getResponse();
        $response = json_decode($response->getContent(), true);
        $ref = $response[0]["reference"];
        $client->request('GET', "/api/offers/".$ref);
        $this->assertResponseIsSuccessful();
    }

    public function testBusinessApi()
    {
        $client = static::createClient();
        $client->request('GET', '/api/business');
        $this->assertResponseIsSuccessful();
    }

    public function testSingleBusinessApi()
    {
        $client = static::createClient();
        $client->request('GET', '/api/business');
        $response = $client->getResponse();
        $response = json_decode($response->getContent(), true);
        $id = $response[0]["id"];
        $client->request('GET', "/api/business/$id");
        $this->assertResponseIsSuccessful();
    }
}