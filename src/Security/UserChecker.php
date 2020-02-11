<?php

namespace App\Security;

use App\Entity\User as AppUser;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Exception\DisabledException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class UserChecker
 * @package App\Security
 */
class UserChecker implements UserCheckerInterface
{
    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * UserChecker constructor.
     * @param RouterInterface $router
     * @param SessionInterface $session
     */
    public function __construct(RouterInterface $router, SessionInterface $session)
    {
        $this->router = $router;
        $this->session = $session;
    }

    /**
     * @param UserInterface $user
     */
    public function checkPreAuth(UserInterface $user)
    {
        if (!$user instanceof AppUser) {
            return;
        }

        if ($user->getIsActive() === false) {
            $ex = new DisabledException();
            $ex->setUser($user);
            throw $ex;
        }
    }

    /**
     * @param UserInterface $user
     */
    public function checkPostAuth(UserInterface $user) {}
}