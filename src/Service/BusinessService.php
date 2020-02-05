<?php

namespace App\Service;

use App\Repository\BusinessRepository;
use Symfony\Component\HttpClient\HttpClient;

/**
 * Class BusinessService
 * @package App\Service
 */
class BusinessService {
    /**
     * @var BusinessRepository
     */
    private $businessRepository;

    /**
     * BusinessService constructor.
     * @param BusinessRepository $businessRepository
     */
    public function __construct(BusinessRepository $businessRepository)
    {
        $this->businessRepository = $businessRepository;
    }

    /**
     * @param $value
     * @return bool
     */
    public function isBusinessAlreadyExistInTheDatabase(int $value) :bool
    {
        $business = $this->businessRepository->findOneBy(["siretNumber" => $value]);

        if ($business != null) {
            return true;
        }

        else {
            return false;
        }
    }

    public function isBusinessExist()
    {
        $client = $this->httpClient::create();

        $response = $client->request('GET', 'https://api.github.com/repos/symfony/symfony-docs');

        dd($response);
    }
}