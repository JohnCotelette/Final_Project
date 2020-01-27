<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ApplicationController extends AbstractController
{

    /**
     * @Route("/application/apply", name="application_apply")
     */

    public function Apply()
    {

        return $this->render('application/apply.html.twig');
    }
}
