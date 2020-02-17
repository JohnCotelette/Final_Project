<?php

namespace App\Service;

use App\Entity\Business;
use App\Repository\BusinessRepository;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

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
     * @var ParameterBagInterface
     */
    private $params;

    /**
     * @var Request
     */
    private $request;

    /**
     * BusinessService constructor.
     * @param BusinessRepository $businessRepository
     * @param ParameterBagInterface $params
     * @param RequestStack $requestStack
     */
    public function __construct(BusinessRepository $businessRepository, ParameterBagInterface $params, RequestStack $requestStack)
    {
        $this->businessRepository = $businessRepository;
        $this->params = $params;
        $this->request = $requestStack->getCurrentRequest();
    }

    /**
     * @param int $siret
     * @return bool
     */
    public function isBusinessAlreadyExistInTheDatabase(string $siret) :bool
    {
        $business = $this->businessRepository->findOneBy(["siretNumber" => $siret]);

        if ($business != null) {
            return true;
        }

        else {
            return false;
        }
    }

    /**
     * @param int $siret
     * @return bool
     */
    public function isBusinessExist(string $siret) :bool
    {
        $client = HttpClient::create([
            "auth_bearer" => $this->params->get("api_siren_token"),
        ]);

        $response = $client->request('GET', $this->params->get("api_siren_url_siret") . $siret);

        if ($response->getStatusCode() === 200) {
            $data = json_decode($response->getContent())->etablissement;

            $this->generateDefaultBusinessInfos($data);

            return true;
        }

        else {
            return false;
        }
    }

    /**
     * @param object $data
     * @return void
     */
    public function generateDefaultBusinessInfos(object $data) :void
    {
        $businessName = ucfirst(strtolower($data->uniteLegale->denominationUniteLegale));
        $businessSiret = $data->siret;

        $dataAddress = $data->adresseEtablissement;

        $businessAddress =
            $dataAddress->numeroVoieEtablissement . " "
            . strtolower($dataAddress->typeVoieEtablissement) . " "
            . strtolower($dataAddress->libelleVoieEtablissement) . ", "
            . $dataAddress->codePostalEtablissement . ", "
            . ucfirst(strtolower($dataAddress->libelleCommuneEtablissement))
        ;

        $businessEmployeesNumber = null;

        if ($data->trancheEffectifsEtablissement <= 11) {
            $businessEmployeesNumber = "19 employés et moins";
        } else if ($data->trancheEffectifsEtablissement <= 21) {
            $businessEmployeesNumber = "20 à 99 employés";
        } else if ($data->trancheEffectifsEtablissement <= 32) {
            $businessEmployeesNumber = "100 à 499 employés";
        } else {
            $businessEmployeesNumber = "500 employés et plus";
        }

        $business = new Business();

        $business
            ->setName($businessName)
            ->setSiretNumber($businessSiret)
            ->setEmployeesNumber($businessEmployeesNumber)
            ->setLocation($businessAddress);

        $this->request->attributes->set("businessItem", $business);
    }
}