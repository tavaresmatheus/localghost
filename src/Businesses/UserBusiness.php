<?php

namespace App\Businesses;

use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;



class UserBusiness
{
    private User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function encryptPassword(UserPasswordHasherInterface $passwordHasher): string
    {
        $userPassword = $this->user->getPassword();
        $passwordHasher->hasherPassword();
    }
}