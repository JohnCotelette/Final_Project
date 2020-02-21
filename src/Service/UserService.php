<?php

namespace App\Service;

use App\Entity\User;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

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
     * @var RequestStack
     */
    private $request;

    /**
     * UserService constructor.
     * @param RouterInterface $router
     * @param RequestStack $request
     */
    public function __construct(RouterInterface $router, RequestStack $request, UserPasswordEncoderInterface $encoder)
    {
        $this->router = $router;
        $this->request = $request;
        $this->encoder = $encoder;

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

    public function cryptPassword(User $user)
    {
        $password = $this->encoder->encodePassword($user, $user->getPassword());
        $user->setPassword($password);
    }
}