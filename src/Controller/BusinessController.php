<?php

namespace App\Controller;

use App\Entity\Business;
use App\Repository\ApplicationRepository;
use App\Repository\BusinessRepository;
use App\Repository\OfferRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

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
        $allBusiness = $businessRepository->findBy([], ['name' => 'ASC']);

        $pagination = $paginator->paginate(
            $allBusiness,
            $request->query->getInt('page', 1)/*page number*/,
            10/*limit per page*/
        );
        // dd($pagination);

        return $this->render('business/index.html.twig', [
            'pagination' => $pagination,
        ]);
    }

    /**
     * @Route("/business/{id}", name="show_business", methods={"GET", "POST"})
     * @param Business $business
     * @param OfferRepository $offerRepository
     * @param ApplicationRepository $applicationRepository
     * @return Response
     */
    public function showBusiness(Business $business, OfferRepository $offerRepository, ApplicationRepository $applicationRepository)
    {
        $offers = $offerRepository->findOffersByBusinessOrderByDate($business);
        $lastOffersInWebsite = $offerRepository->findBy([], ['created_at'=>'DESC'], 3 ,0);
        
        return $this->render('business/show.html.twig', [
            'business' => $business,
            'offers' => $offers,
            'lastOffersInWebsite' => $lastOffersInWebsite,
        ]);
    }
}
