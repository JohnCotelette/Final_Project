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
       $offers = $offerRepository->findAll();
       
        return $this->render('home/home.html.twig', [
           'offers' => $offers,
        ]);
    }


}
