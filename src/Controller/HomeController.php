<?php

namespace App\Controller;

use App\Entity\Offer;
use App\Repository\OfferRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     * @param OfferRepository $offerRepository
     * @return Response
     */
    public function index(OfferRepository $offerRepository)
    {
       $offers = $offerRepository->findBy([], ['created_at'=>'DESC'], 6 ,0);

        return $this->render('home/home.html.twig', [
           'offers' => $offers,
        ]);
    }
}
