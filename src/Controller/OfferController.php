<?php

namespace App\Controller;

use App\Entity\Offer;
use App\Repository\OfferRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class OfferController extends AbstractController
{
    /**
     * @Route("/offer", name="offer")
     */
    public function index(OfferRepository $offerRepository, Request $request, PaginatorInterface $paginator)
    {
        $query = $offerRepository->findAll();
        
        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1)/*page number*/,
            10/*limit per page*/
        );

        return $this->render('offer/index.html.twig', [
            'pagination' => $pagination,

        ]);
    }

    /**
     * @Route("/offer/{id}", name="show_offer", methods={"GET", "POST"})
     */
    public function show(Offer $offer)
    {
        return $this->render('offer/show.html.twig', [
            'offer' => $offer,
        ]);   
    }

    
}
