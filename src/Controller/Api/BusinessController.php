<?php

namespace App\Controller\Api;

use App\Entity\Business;
use App\Repository\BusinessRepository;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;

/**
 * Class BusinessController
 * @package App\Controller\Api
 */
class BusinessController extends AbstractFOSRestController
{
    /**
     * @Rest\Get("/api/business")
     * @Rest\View(serializerGroups={"lightBusiness"})
     * @param BusinessRepository $businessRepository
     * @return Business[]
     */
    public function listBusiness(BusinessRepository $businessRepository)
    {
        return $businessRepository->findAll();
    }

    /**
     * @Rest\Get("/api/business/{id}")
     * @Rest\View(serializerGroups={"detailedBusiness"})
     * @param Business $business
     * @return Business|View|null
     */
    public function getBusiness(Business $business)
    {
        return $business;
    }
}