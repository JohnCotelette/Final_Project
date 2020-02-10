<?php

namespace App\Service;


use App\Entity\Business;
use App\Entity\Offer;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpClient\HttpClient;

class MapService {

    /**
     * @var ParameterBagInterface
     */
    private $params;

    public function __construct(ParameterBagInterface $params)
    {
        $this->params = $params;
    }

    function getMap(object $object): array
    {
        $key = $this->params->get("api_google_map_key");
        $location = $object->getLocation();
        $client = HttpClient::create();
        $response = $client->request('GET', "https://maps.googleapis.com/maps/api/geocode/json?address=$location&key=$key");
        $statusCode = $response->getStatusCode();
        $content = json_decode($response->getContent());
        if ($content->status === "ZERO_RESULTS") {
            $lat = 48.866667;
            $lng = 2.333333;
        }

        else {
            $content = $content->results[0];
            $lat = $content->geometry->location->lat;
            $lng = $content->geometry->location->lng;
        }

        $informations = [
            'lat' => $lat,
            'lng' =>  $lng,
        ];
        
        return $informations;
    }
}