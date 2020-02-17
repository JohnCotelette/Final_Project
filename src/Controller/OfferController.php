<?php

namespace App\Controller;

use App\Entity\Offer;
use App\Form\ApplyType;
use App\Form\CategoriesType;
use App\Entity\Application;
use App\Form\OfferType;
use App\Service\OfferService;
use App\Repository\OfferRepository;
use App\Service\MailService;
use App\Service\MapService;
use App\Service\UserService;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * Class OfferController
 * @package App\Controller
 */
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
        $location = null;

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
            $location = $form["location"]->getData();
        }

        $offers = $offerRepository->findByCategoriesOrderByDate($category, $experience, $salary, $type, $location);

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
     * @Route("/offer/create", name="offer_create", methods={"GET", "POST"})
     * @param Request $request
     * @param OfferService $offerService
     * @return RedirectResponse|Response
     */
    public function createOffer(Request $request, OfferService $offerService)
    {
        $user = $this->getUser();
        $offer = new Offer();

        $form = $this->createForm(OfferType::class, $offer);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $offer = $form->getData();

            $hasAldreadyCreatedAnOfferWithSameName = $offerService->checkIfOfferAlreadyExistForThisUser($user, $offer);

            if ($hasAldreadyCreatedAnOfferWithSameName == true) {
                $this->addFlash("errorSameOffer", "Vous avez déjà créé une annonce avec le même intitulé de poste");

                return $this->redirectToRoute("offer_create");
            }

            $offerService->generateReference($offer);

            $offer->setUser($user);

            $entityManager = $this
                ->getDoctrine()
                ->getManager();

            $entityManager->persist($offer);
            $entityManager->flush();

            $this->addFlash("successOfferCreated", "Votre annonce a bien ajoutée !");

            return $this->redirectToRoute("show_offer", ["reference" => $offer->getReference()]);
        }

        return $this->render("offer/create.html.twig", [
            "form" => $form->createView(),
        ]);
    }

    /**
     * @Route("/offer/edit/{reference}", name="edit_offer", methods={"GET", "POST"})
     * @isGranted("OFFER_EDIT", subject="offer")
     * @param OfferRepository $offerRepository
     * @param string $reference
     * @param Offer $offer
     * @param Request $request
     * @return Response
     */
    public function editOffer(OfferRepository $offerRepository, string $reference, Offer $offer, Request $request)
    {
        $form = $this->createForm(OfferType::class, $offer);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this
                ->getDoctrine()
                ->getManager();

            $entityManager->flush();

            $this->addFlash("successOfferUpdated", "Votre annonce a bien modifiée !");

            return $this->redirectToRoute("show_offer", ["reference" => $offer->getReference()]);
        }

        return $this->render("offer/edit.html.twig", [
            "form" => $form->createView(),
            "offer" => $offer,
        ]);
    }

    /**
     * @Route("/offer/{reference}", name="show_offer", methods={"GET", "POST"})
     * @param OfferService $offerService
     * @param Request $request
     * @param MailService $mailService
     * @param OfferRepository $offerRepository
     * @param string $reference
     * @return Response
     */
    public function showOffer(OfferService $offerService, Request $request, MailService $mailService, OfferRepository $offerRepository, string $reference, MapService $mapService)
    {
        $user = $this->getUser();

        $offer = $offerRepository->findOneBy(["reference" => $reference]);

        $checkApply = $offerService->checkIfCandidateAlreadyApply($user ,$offer);

        $location = $mapService->getMap($offer);

        if ($user && $user->getRoles() === ["ROLE_CANDIDATE"]) {
            $application = new Application;

            $form = $this->createForm(ApplyType::class, $application);

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {

                if ($user->getRoles() === ["ROLE_CANDIDATE"] && $user->getCv() != null) {
                    $checkApply = $offerService->checkIfCandidateAlreadyApply($user ,$offer);

                    if ($checkApply != true) {
                        $entityManager = $this
                            ->getDoctrine()
                            ->getManager();

                        $application->setUser($user);
                        $offer->addApplication($application);

                        $entityManager->persist($application);
                        $entityManager->flush();

                        $mailService->sendMailToConfirmApply($user, "confirmApply", $offer);

                        $this->addFlash(
                            "success",
                            "Votre candidature a bien été enregistrée, vous recevrez prochainement une réponse de la part de " . $offer->getUser()->getBusiness()->getName() . "."
                        );

                        $checkApply = true;
                    }
                    else {
                        $this->addFlash("errorAlreadyApply", "Vous avez déjà postulé à cette annonce");
                    }
                }
            }
        }

        return $this->render('offer/show.html.twig', [
            'form' => !empty($form) ? $form->createView() : null,
            'offer' => $offer,
            'checkApply' => $checkApply,
            'location' => $location,
        ]);
    }
}
