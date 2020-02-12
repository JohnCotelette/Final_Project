<?php

namespace App\Service;

use App\Entity\Offer;
use App\Entity\Application;
use App\Entity\User;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

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

    /**
     * @param User $user
     * @param Offer $offer
     * @return bool
     */
    public function checkIfCandidateAlreadyApply(?User $user, Offer $offer) :bool
    {
        if ($user) {
            $applicationsOfThisOffer = $offer->getApplications();

            if ($applicationsOfThisOffer) {
                forEach($applicationsOfThisOffer as $application) {
                    if ($application->getUser() === $user)
                    {
                        return true;
                    }
                }
            }
        }

        return false;
    }

    /**
     * @param User $user
     * @param Offer $offer
     * @return bool
     */
    public function checkIfOfferAlreadyExistForThisUser(User $user, Offer $offer) :bool
    {
        $offers = $user->getOffers();

        foreach ($offers as $lastOffer) {
            if ($lastOffer->getTitle() === $offer->getTitle()) {
                return true;
            }
        }

        return false;
    }
}