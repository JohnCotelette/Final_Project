<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RecruiterController extends AbstractController {
    /**
     * @Route("/recruiter/", name="recruiter_index", methods={"GET", "POST"})
     * @param UserRepository $userRepository
     * @param UserService $userService
     * @return RedirectResponse|Response
     */
    public function index(UserRepository $userRepository,  UserService $userService) {

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

        dd($candidates);

        return $this->render("recruiter/index.html.twig", []);
    }
}