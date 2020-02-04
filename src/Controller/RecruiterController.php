<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Service\UserService;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RecruiterController extends AbstractController {
    /**
     * @Route("/recruiter/", name="recruiter_index", methods={"GET", "POST"})
     * @param Request $request
     * @param UserRepository $userRepository
     * @param UserService $userService
     * @param PaginatorInterface $paginator
     * @return RedirectResponse|Response
     */
    public function index(Request $request, UserRepository $userRepository,  UserService $userService, PaginatorInterface $paginator) {

        $user = $this->getUser();

        if ($user) {
            if ($user->getRoles() === ["ROLE_CANDIDATE"]) {
                return $userService->redirectBasedOnRoles($user);
            }
        }
        else {
            return $userService->redirectBasedOnRoles(null);
        }

        $candidates = $userRepository->getCandidatesWithPublicProfilOrderByDates();

        $pagination = $paginator->paginate(
            $candidates,
            $request->query->getInt('page', 1),
            10
        );

        return $this->render("recruiter/index.html.twig", [
            "pagination" => $pagination,
        ]);
    }
}