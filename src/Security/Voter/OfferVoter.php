<?php

namespace App\Security\Voter;

use App\Entity\Offer;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class OfferVoter
 * @package App\Security\Voter
 */
class OfferVoter extends Voter
{
    /**
     * @param string $attribute
     * @param mixed $subject
     * @return bool
     */
    protected function supports($attribute, $subject)
    {
        return in_array($attribute, ["OFFER_EDIT"])
            && $subject instanceof Offer;
    }

    /**
     * @param string $attribute
     * @param Offer $subject
     * @param TokenInterface $token
     * @return bool
     */
    protected function voteOnAttribute($attribute, $subject, TokenInterface $token) :bool
    {
        $user = $token->getUser();

        if (!$user instanceof UserInterface) {
            return false;
        }

        switch ($attribute) {
            case "OFFER_EDIT":
                if ($subject->getUser() === $user) {
                    return true;
                }

                return false;
        }

        return false;
    }
}
