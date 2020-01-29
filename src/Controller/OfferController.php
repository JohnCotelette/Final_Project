<?php

namespace App\Controller;

use App\Entity\Application;
use App\Entity\Offer;
use App\Form\ApplyType;
use App\Repository\FieldRepository;
use App\Repository\OfferRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class OfferController extends AbstractController
{
    /**
     * @Route("/offers", name="offers_index")
     * @param OfferRepository $offerRepository
     * @param Request $request
     * @param PaginatorInterface $paginator
     * @param FieldRepository $fieldRepository
     * @return Response
     */
    public function index(OfferRepository $offerRepository, Request $request, PaginatorInterface $paginator, FieldRepository $fieldRepository)
    {
        $query = $offerRepository->findAll();
        $fields = $fieldRepository->findAll();
        
        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1)/*page number*/,
            10/*limit per page*/
        );

        return $this->render('offer/index.html.twig', [
            'fields' => $fields,
            'pagination' => $pagination,
        ]);
    }

    /**
     * @Route("/offer/{id}", name="show_offer", methods={"GET", "POST"})
     * @param Offer $offer
     * @return Response
     */
    public function showOffer(Offer $offer, Request $request)
    {

        $application = new Application();


        $user = $this->getUser();

        $applicationsOfThisOffer = $offer->getApplications();
        forEach($applicationsOfThisOffer as $application) {
            if ($application->getUser() === $user)
            {	
                $this->addFlash("error", "Vous avez déjà postulé à cette annonce");

                return $this->redirectToRoute("offers_index");
            }
        }

        if ($user && $user->getRoles()) {
            $form = $this->createForm(ApplyType::class, $application);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid() ) {
                $entityManager = $this
                ->getDoctrine()
                ->getManager();

                $application->setUser($user);
                $offer->addApplication($application);
                
                $entityManager->persist($application);
                $entityManager->flush();
            }

            return $this->render('offer/show.html.twig', [
                'application' => $application,
                'form' => $form->createView(),
                'offer' => $offer,
            ]);
        }
        else {
            return $this->render('offer/show.html.twig', [
                'application' => $application,
                'offer' => $offer,
            ]);
        }
          
    }

    // /**
    //  * @Route("offer/{id}/apply", name="offer_apply")
    //  */
    // public function apply(Offer $offer, Request $request)
    // {
        
    // }
}
