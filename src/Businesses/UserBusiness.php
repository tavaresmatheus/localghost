<?php

namespace App\Businesses;

use App\Entity\User;
use App\Repository\UserRepositoryInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserBusiness
{
    private UserPasswordHasherInterface $passwordHasher;
    private UserRepositoryInterface $userRepository;

    public function __construct(
        UserPasswordHasherInterface $passwordHasher,
        UserRepositoryInterface $userRepository
    )
    {
        $this->passwordHasher = $passwordHasher;
        $this->userRepository = $userRepository;
    }

    public function createUser(User $user): array
    {
        $userInformation = $this->userRepository->save($user);
        return $userInformation;
    }

    public function removeUser(string $userId): string
    {
        $userInformation = $this->userRepository->remove($userId);
        return $userInformation;
    }

    public function findUserById(string $userId): ?User
    {
        $userInformation = $this->userRepository->findById($userId);
        return $userInformation;
    }

    public function encryptPassword(string $userPassword): string
    {
        $user = new User();
        $hashedPassword = $this->passwordHasher->hashPassword(
            $user,
            $userPassword
        );
        return $hashedPassword;
    }
}