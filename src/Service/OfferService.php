<?php

namespace App\Service;

use App\Entity\Offer;

/**
 * Class OfferService
 */
class OfferService
{
    /**
     * @param Offer $offer
     */
    public function generateReference(Offer $offer)
    {
        $reference = rand(0, 10000) . time() . $offer->getId();

        $offer->setReference($reference);
    }
}