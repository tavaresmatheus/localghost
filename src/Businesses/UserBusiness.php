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

        return $this->userRepository->save($user);
    }

    public function removeUser(string $userId): ?array
    {
        $user = $this->findUserById($userId);
        if (empty($user)) {
            return null;
        }

        $this->userRepository->remove($userId);

        return [
            'id' => $userId,
            'name' => $user->getName(),
            'email' => $user->getEmail(),
            'roles' => $user->getRoles(),
        ];
    }

    public function findUserById(string $userId): ?User
    {
        return $this->userRepository->findById($userId);
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

    public function updateUser(
        array $userInformation,
        string $userId
    ): ?array
    {
        $validationErrors = $this->validator->validate($userInformation);
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

        if (
            !empty($userInformation['email']) &&
            !empty($this->findUserByEmail($userInformation['email']))
        ) {
            return [
                'errors' => 'This email is already in use.',
            ];
        }

        $repositoryResponse = $this->userRepository->update(
            $userInformation,
            $userId
        );

        if ($repositoryResponse === null) {
            return [
                'errors' => 'Nothing was changed.',
            ];
        }

        return $repositoryResponse;
    }

    public function encryptPassword(string $userPassword): string
    {
        $user = new User();

        return $this->passwordHasher->hashPassword(
            $user,
            $userPassword
        );
    }
}