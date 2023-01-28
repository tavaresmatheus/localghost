<?php

namespace App\Businesses;

use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserBusiness
{
    private User $user;
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(
        User $user,
        UserPasswordHasherInterface $passwordHasher
    )
    {
        $this->user = $user;
        $this->passwordHasher = $passwordHasher;
    }

    public function encryptPassword(string $userPassword): string
    {
        $hashedPassword = $this->passwordHasher->hashPassword(
            $this->user,
            $userPassword
        );
        return $hashedPassword;
    }
}