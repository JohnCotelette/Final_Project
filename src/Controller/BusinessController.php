<?php

namespace App\Controller;

use App\Entity\Offer;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class BusinessController extends AbstractController
{
    /**
     * @Route("/business", name="business")
     */
    public function index()
    {
        return $this->render('business/index.html.twig', [
            'controller_name' => 'BusinessController',
        ]);
    }

    /**
     * @Route("/business/{id}", name="show_business", methods={"GET", "POST"})
     */
    public function showBusiness(Offer $offer, UserRepository $userRepository)
    {

        
        return $this->render('business/show.html.twig', [
            'business' => $business,
        ]);   
    }

}
