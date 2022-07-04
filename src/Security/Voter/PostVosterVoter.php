<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class PostVosterVoter extends Voter
{
    public const EDIT = 'PRODUCT_EDIT';
    public const CREAT = 'PRODUCT_CREAT';

    public function __construct(Security $security){
        $this->security = $security;
    }

    protected function supports(string $attribute, $subject): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, [self::EDIT, self::CREAT])
            && $subject instanceof \App\Entity\Produit;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case self::EDIT:
                // logic to determine if the user can EDIT
                // return true or false
                if ($this->security->isGranted("ROLE_GESTIONNAIRE")) {return true;}

                break;
            case self::CREAT:
                if ($this->security->isGranted("ROLE_GESTIONNAIRE")) {return true;}
    
                // logic to determine if the user can VIEW
                // return true or false
                break;
        }

        return false;
    }
}