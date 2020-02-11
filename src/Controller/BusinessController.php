<?php

namespace App\Controller;

use App\Entity\Business;
use App\Repository\OfferRepository;
use App\Repository\BusinessRepository;
use App\Repository\ApplicationRepository;
use App\Service\MapService;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BusinessController extends AbstractController
{
    /**
     * @Route("/business", name="all_business")
     * @param BusinessRepository $businessRepository
     * @param Request $request
     * @param PaginatorInterface $paginator
     * @return Response
     */
    public function index(BusinessRepository $businessRepository, Request $request, PaginatorInterface $paginator)
    {
        $allBusiness = $businessRepository->getAllBusinessWhichHaveOffers();

        $pagination = $paginator->paginate(
            $allBusiness,
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('business/index.html.twig', [
            'pagination' => $pagination,
        ]);
    }

    /**
     * @Route("/business/{id}", name="show_business", methods={"GET", "POST"})
     * @param Business $business
     * @param OfferRepository $offerRepository
     * @return Response
     */
    public function showBusiness(Business $business, OfferRepository $offerRepository, MapService $mapService)
    {
        $offers = $offerRepository->findOffersByBusinessOrderByDate($business);
        $lastOffersInWebsite = $offerRepository->findBy([], ['created_at'=>'DESC'], 3 ,0);

        $location = $mapService->getMap($business);

        return $this->render('business/show.html.twig', [
            'business' => $business,
            'offers' => $offers,
            'lastOffersInWebsite' => $lastOffersInWebsite,
            'location' => $location,
        ]);
    }
}
