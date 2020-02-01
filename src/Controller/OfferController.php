<?php

namespace App\Controller;

use App\Entity\Offer;
use App\Form\ApplyType;
use App\Form\CategoriesType;
use App\Entity\Application;
use App\Service\OfferService;
use App\Repository\OfferRepository;
use App\Service\MailService;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class OfferController extends AbstractController
{
    /**
     * @Route("/offers", name="offers_index", methods={"GET", "POST"})
     * @param OfferRepository $offerRepository
     * @param Request $request
     * @param PaginatorInterface $paginator
     * @return Response
     */
    public function index(OfferRepository $offerRepository, Request $request, PaginatorInterface $paginator)
    {
        $category = null;
        $experience = null;
        $salary = null;
        $type = null;
        $city = null;

        $form = $this->createForm(CategoriesType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $category = null;

            if ($form["category"]->getData()) {
                $category = $form["category"]->getData();
            }

            $experience = $form["experience"]->getData();
            $salary = $form["salary"]->getData();
            $type = $form["type"]->getData();
            $city = $form["city"]->getData();
        }

        $offers = $offerRepository->findByCategoriesOrderByDate($category, $experience, $salary, $type, $city);

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
     * @param MailService $mailService
     * @return Response
     */
    public function showOffer(OfferService $offerService, Offer $offer, Request $request, MailService $mailService)
    {
        $application = new Application;
        $user = $this->getUser();

        $checkApply = $offerService->checkIfCandidateAlreadyApply($offer, $application);
    
        if ($user && $user->getRoles() === ["ROLE_CANDIDATE"]) {
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

                $checkApply = true;

                $mailService->sendMailToConfirmApply($user, "confirmApply", $offer);

                $this->addFlash("success", "Votre candidature a bien été enregistrée, vous recevrez prochainement une réponse de la part de l'auteur de cette offre.");
            }
        }
                    
        return $this->render('offer/show.html.twig', [
            'application' => $application,
            'form' => !empty($form) ? $form->createView() : null,
            'offer' => $offer,
            'checkApply' => $checkApply,
        ]);
    }
}
