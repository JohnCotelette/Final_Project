<?php

namespace App\Controller;

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
}
