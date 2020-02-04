<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\RouterInterface;
use App\Entity\User;

/**
 * Class UserService
 * @package App\Service
 */
class UserService
{
    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * UserService constructor.
     * @param RouterInterface $router
     */
    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    /**
     * @param User $user
     * @return RedirectResponse
     */
    public function redirectBasedOnRoles(?User $user)
    {
        if ($user) {
            if ($user->getRoles() === ["ROLE_RECRUITER"]) {
                return new RedirectResponse($this->router->generate("offer_create"));
            }
            else if ($user->getRoles() === ["ROLE_ADMIN"]) {
                return new RedirectResponse($this->router->generate("easyadmin"));
            }
            else {
                return new RedirectResponse($this->router->generate("offers_index"));
            }
        }
        else {
            return new RedirectResponse($this->router->generate("login"));
        }
    }

    /**
     * @param User $user
     */
    public function generatePasswordToken(User $user)
    {
        $passwordToken = md5($user->getEmail().uniqid());

        $user->setPasswordToken($passwordToken);
    }
}