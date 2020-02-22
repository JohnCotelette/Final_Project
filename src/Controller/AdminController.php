<?php

namespace App\Controller;

use App\Repository\BusinessRepository;
use App\Repository\UserRepository;
use EasyCorp\Bundle\EasyAdminBundle\Controller\EasyAdminController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class AdminController
 * @package App\Controller
 */
class AdminController extends EasyAdminController
{
    /**
     * @Route("/admin/dashboard", name="admin_dashbord")
     * @param BusinessRepository $businessRepository
     * @param UserRepository $userRepository
     * @return Response
     */
    public function index()
    {
        return $this->render('bundles/EasyAdminBundle/default\index.html.twig', [
        ]);
    }
}
