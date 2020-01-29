<?php

namespace App\Service;

use App\Entity\Offer;
use App\Form\ApplyType;
use App\Entity\Application;
use Symfony\Component\HttpFoundation\Request;

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

    public function checkIfCandidateAlreadyApply(Offer $offer, Request $request)
    {
        $application = new Application();
        $user = $this->getUser();
        $hasAlreadyApply = null;
        
        if ($user && $user->getRoles() === ["ROLE_CANDIDATE"]) {
            
            $form = $this->createForm(ApplyType::class, $application);
            $form->handleRequest($request);

            $applicationsOfThisOffer = $offer->getApplications();
            
            forEach($applicationsOfThisOffer as $application) {
                if ($application->getUser() === $user)
                {	
                    $this->addFlash("error", "Vous avez déjà postulé à cette annonce");
                    return $hasAlreadyApply = false;

                }
            }

            if ($form->isSubmitted() && $form->isValid() ) {

                $entityManager = $this->getDoctrine()->getManager();

                $application->setUser($user);
                $offer->addApplication($application);
                
                $entityManager->persist($application);
                $entityManager->flush();

                $this->addFlash("success", "Vous avez postulé");
                return $hasAlreadyApply = true;
            }           
        }
        return $this->render('offer/show.html.twig', [
            'application' => $application,
            'offer' => $offer,
            'hasAlreadyApply' => $hasAlreadyApply,
            'form' => $form->createView(),
        ]);    
    }
}