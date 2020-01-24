<?php

namespace App\Controller;

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
        $queryBuilder = $offerRepository->findAll();

        $pagination = $paginator->paginate(
            $queryBuilder,
            $request->query->getInt('page', 1)/*page number*/,
            10/*limit per page*/
        );
        return $this->render('offer/index.html.twig', [
            'pagination' => $pagination,
        ]);
    }
}
