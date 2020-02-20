<?php

namespace App\Controller;

use App\Repository\BusinessRepository;
use App\Repository\UserRepository;
use EasyCorp\Bundle\EasyAdminBundle\Controller\EasyAdminController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class AdminController
 * @package App\Controller
 */
class AdminController extends EasyAdminController
{
    /**
     * @Route("/admin/dashboard", name="admin_dashbord")
     */
    public function index()
    {
        return $this->render('bundles/EasyAdminBundle/default\index.html.twig', [
        ]);
    }
}
