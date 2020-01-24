<?php

namespace App\Controller;

use App\Entity\Offer;
use App\Repository\OfferRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(OfferRepository $offerRepository )
    {
        //Display the  last five offers
       $offers = $offerRepository->findBy([], ['created_at'=>'DESC'], 3 ,0);
       $lastoffers = array_slice($offers, 0, 5);  
        return $this->render('home/home.html.twig', [
           'offers' => $offers,
        ]);
    }


}
