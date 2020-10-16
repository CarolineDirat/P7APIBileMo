<?php

namespace App\Security\Voter;

use App\Entity\Client;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class UserByClientVoter extends Voter
{
    public const CLIENT = 'client';

    private const ATTRIBUTES = [
        self::CLIENT,
    ];

    /**
     * isOwner
     * Return true if the user belongs to the Client.
     *
     * @param UserInterface $client
     * @param User          $user
     *
     * @return bool
     */
    public function isOwner(UserInterface $client, User $user): bool
    {
        return $user->getClient() === $client;
    }

    /**
     * supports.
     *
     * @param string $attribute
     * @param User   $subject
     *
     * @return bool
     */
    protected function supports($attribute, $subject): bool
    {
        return in_array($attribute, self::ATTRIBUTES)
            && $subject instanceof User;
    }

    /**
     * voteOnAttribute
     * It is safe to assume that $attribute and $subject already passed the "supports()" method check.
     *
     * @param string         $attribute
     * @param User           $subject
     * @param TokenInterface $token
     *
     * @return bool
     */
    protected function voteOnAttribute($attribute, $subject, TokenInterface $token): bool
    {
        $client = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$client instanceof UserInterface) {
            return false;
        }

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case self::CLIENT:
                // logic to determine if the client can access to the user
                // return true or false
                return $this->isOwner($client, $subject);
        }

        return false;
    }
}
