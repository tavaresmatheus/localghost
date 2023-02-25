<?php

namespace App\Businesses;

use App\Entity\User;
use App\Repository\UserRepositoryInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserBusiness
{
    private UserPasswordHasherInterface $passwordHasher;
    private UserRepositoryInterface $userRepository;
    private ValidatorInterface $validator;

    public function __construct(
        UserPasswordHasherInterface $passwordHasher,
        UserRepositoryInterface $userRepository,
        ValidatorInterface $validator
    )
    {
        $this->passwordHasher = $passwordHasher;
        $this->userRepository = $userRepository;
        $this->validator = $validator;
    }

    public function createUser(User $user): array
    {
        $validationErrors = $this->validator->validate($user);
        $errorQuantity = count($validationErrors);
        if ($errorQuantity > 0) {
            $errors = [];
            for ($counter = 0; $counter < $errorQuantity; $counter++) {
                $errors[$validationErrors->get($counter)->getPropertyPath()] = $validationErrors->get($counter)->getMessage();
            }

            return [
                'errors' => $errors
            ];
        }

        if (!empty($this->findUserByEmail($user->getEmail()))) {
            return [
                'errors' => 'This email is already in use.',
            ];
        }

        $userCreated = $this->userRepository->create($user);

        $userInformation = $this->findUserById($userCreated['id']);

        $userInformation = [
            'id' => $userInformation->getId(),
            'name' => $userInformation->getName(),
            'email' => $userInformation->getEmail(),
            'roles' => $userInformation->getRoles(),
        ];

        return $userInformation;
    }

    public function removeUser(string $userId): ?array
    {
        $user = $this->findUserById($userId);
        if (empty($user)) {
            return null;
        }

        $this->userRepository->delete($userId);

        return [
            'id' => $userId,
            'name' => $user->getName(),
            'email' => $user->getEmail(),
            'roles' => $user->getRoles(),
        ];
    }

    public function findUserById(string $userId): ?User
    {
        $userInformation = $this->userRepository->findById($userId);

        return $userInformation;
    }

    public function findUserByEmail(string $userEmail): ?User
    {
        $userInformation = $this->userRepository->findByEmail($userEmail);

        return $userInformation;
    }

    public function listAllUsers(): array
    {
        $allUsers = [];
        $userInformations = $this->userRepository->listAll();
        foreach ($userInformations as $userInformation) {
            $allUsers[] = [
                'id' => $userInformation->getId(),
                'name' => $userInformation->getName(),
                'email' => $userInformation->getEmail(),
                'role' => $userInformation->getRoles(),
            ];
        }

        return $allUsers;
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