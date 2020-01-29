<?php

namespace App\Controller;

use App\Entity\Offer;
use App\Form\ApplyType;
use App\Form\CategoriesType;
use App\Repository\FieldRepository;
use App\Entity\Application;
use App\Service\OfferService;
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
        $offers = $offerRepository->findAll();

        $form = $this->createForm(CategoriesType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $category = null;

            if ($form["category"]->getData()) {
                $category = $form["category"]->getData()->getName();
            }

            $experience = $form["experience"]->getData();
            $salary = $form["salary"]->getData();

            $offers = $offerRepository->findByCategories($category, $experience, $salary);
        }

        $pagination = $paginator->paginate(
            $offers,
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('offer/index.html.twig', [
            "pagination" => $pagination,
            "form" => $form->createView(),
        ]);
    }

    /**
     * @Route("/offer/{id}", name="show_offer", methods={"GET", "POST"})
     * @param OfferService $offerService
     * @param Offer $offer
     * @param Request $request
     * @return Response
     */
    public function showOffer(OfferService $offerService, Offer $offer, Request $request)
    {
        $checkApply = $offerService->checkIfCandidateAlreadyApply($offer, $request);
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
                    $hasAlreadyApply = false;

                    return $this->render('offer/show.html.twig', [
                        'application' => $application,
                        'form' => $form->createView(),
                        'offer' => $offer,
                        'hasAlreadyApply' => $hasAlreadyApply,
                    ]);
                }
            }

            if ($form->isSubmitted() && $form->isValid() ) {

                $entityManager = $this
                ->getDoctrine()
                ->getManager();

                $application->setUser($user);
                $offer->addApplication($application);
                
                $entityManager->persist($application);
                $entityManager->flush();

                $hasAlreadyApply = true;

                $this->addFlash("success", "Vous avez postulé");
            }
                        
            return $this->render('offer/show.html.twig', [
                'application' => $application,
                'form' => $form->createView(),
                'offer' => $offer,
                'hasAlreadyApply' => $hasAlreadyApply,
            ]);
            
        }
            
        else {
            return $this->render('offer/show.html.twig', [
                'application' => $application,
                'offer' => $offer,
            ]);
        }     
    }
}
