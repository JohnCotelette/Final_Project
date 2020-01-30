<?php

namespace App\Service;

use App\Entity\Offer;
use App\Entity\Application;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

/**
 * Class OfferService
 */
class OfferService
{
    private $security;
    private $bag;

    const LETTERS = ["A", "C", "Y", "Z"];

    public function __construct(Security $security, FlashBagInterface $bag) {
        $this->security = $security;
        $this->bag = $bag;

    }

    /**
     * @param Offer $offer
     */
    public function generateReference(Offer $offer)
    {
        $reference = rand(10000, 99999) . rand(10000, 99999) . self::LETTERS[rand(0, 3)];

        $offer->setReference($reference);
    }

    public function checkIfCandidateAlreadyApply(Offer $offer, Application $application)
    {        
        $user = $this->security->getUser();
        $hasAlreadyApply = null;

        $applicationsOfThisOffer = $offer->getApplications();
        
        forEach($applicationsOfThisOffer as $application) {
            if ($application->getUser() === $user)
            {	
                $flashbag = $this->bag->add("errorHasAlreadyApply", "T'as déjà postulé à cette annonce");
                return $hasAlreadyApply = false;
            }
        } 
    }
}