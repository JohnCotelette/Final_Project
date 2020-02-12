<?php

namespace App\Controller\Api;

use App\Entity\Business;
use App\Repository\BusinessRepository;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Response;

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
     * @param int $id
     * @param BusinessRepository $businessRepository
     * @return Business|View|null
     */
    public function getBusiness(int $id, BusinessRepository $businessRepository)
    {
        $business = $businessRepository->find($id);

        if (empty($business)) {
            return $this->businessNotFound();
        }

        return $business;
    }

    /**
     * @return View
     */
    private function businessNotFound() {
        return $this->view(["Business not found : " => "L'entreprise avec cet identifiant n'existe pas"], Response::HTTP_NOT_FOUND);
    }
}