<?php

namespace App\Controller\Api;

use App\Entity\Offer;
use App\Repository\OfferRepository;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class OfferController
 * @package App\Controller\Api
 */
class OfferController extends AbstractFOSRestController
{
    /**
     * @Rest\Get("/api/offers")
     * @Rest\View(serializerGroups={"lightOffer"})
     * @param OfferRepository $offerRepository
     * @return Offer[]
     */
    public function listOffers(OfferRepository $offerRepository)
    {
        return $offerRepository->findAll();
    }

    /**
     * @Rest\Get("/api/offers/{reference}")
     * @Rest\View(serializerGroups={"detailedOffer"})
     * @param Offer $offer
     * @return Offer
     */
    public function getOffer(Offer $offer)
    {
        return $offer;
    }
}