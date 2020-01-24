<?php

namespace App\Service;

use App\Entity\Offer;

/**
 * Class OfferService
 */
class OfferService
{
    const LETTERS = ["A", "C", "Y", "Z"];

    /**
     * @param Offer $offer
     */
    public function generateReference(Offer $offer)
    {
        $reference = rand(10000, 99999) . rand(10000, 99999) . self::LETTERS[rand(0, 3)];

        $offer->setReference($reference);
    }
}