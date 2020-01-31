<?php

namespace App\Controller;

use App\Entity\Business;
use App\Repository\BusinessRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

class BusinessController extends AbstractController
{
    /**
     * @Route("/business", name="all_business")
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
     * @Route("/business", name="show_business")
     */
    public function showBusiness(Type $var = null)
    {
        # code...
    }
}
