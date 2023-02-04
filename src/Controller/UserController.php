<?php

namespace App\Controller;

use App\Businesses\UserBusiness;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    private UserBusiness $userBusiness;

    public function __construct(UserBusiness $userBusiness)
    {
        $this->userBusiness = $userBusiness;
    }

    #[Route('/users', name: 'users')]
    public function registerUser(Request $request): JsonResponse
    {
        $requestContent = $request->getContent();
        $userInformation = json_decode($requestContent, true);

        $user = new User();
        $user->setName($userInformation['name']);
        $user->setEmail($userInformation['email']);
        $user->setPassword($this->userBusiness->encryptPassword($userInformation['password']));
        $user->setRoles($userInformation['role']);
        $businessMessage = $this->userBusiness->createUser($user);

        if (key_exists('errors', $businessMessage)) {
            return $this->json(
                [
                    'message' => 'Could not create the user.',
                    'errors' => $businessMessage['errors'],
                ],
                422
            );
        }

        return $this->json(
            [
                'message' => 'User created successfuly.',
                'userInformation' => [
                    'id' => $businessMessage['id'],
                    'name' => $businessMessage['name'],
                    'e-mail' => $businessMessage['email'],
                    'roles' => $businessMessage['roles'],
                ],
            ],
            201
        );
    }

    #[Route('/users/{id}', name: 'users')]
    public function removeUser(string $id): JsonResponse
    {
        $businessMessage = $this->userBusiness->removeUser($id);

        if (empty($businessMessage)) {
            return $this->json(
                [
                    'message' => 'User does not exists.',
                ],
                422
            );
        }

        return $this->json(
            [
                'message' => 'User removed successfuly.',
                'userInformation' => [
                    'id' => $businessMessage['id'],
                    'name' => $businessMessage['name'],
                    'e-mail' => $businessMessage['email'],
                    'roles' => $businessMessage['roles'],
                ],
            ],
            202
        );
    }

    #[Route('/users/{id}', name: 'users')]
    public function detailUser(string $id): JsonResponse
    {
        $user = $this->userBusiness->findUserById($id);

        if (empty($user)) {
            return $this->json(
                [
                    'message' => 'User not found.',
                ],
                422
            );
        }

        return $this->json(
            [
                'message' => 'User detailed successfuly.',
                'userInformation' => [
                    'id' => $user->getId(),
                    'name' => $user->getName(),
                    'e-mail' => $user->getEmail(),
                    'roles' => $user->getRoles(),
                ],
            ],
            200
        );
    }

    #[Route('/users', name: 'users')]
    public function listUsers(): JsonResponse
    {
        $users = $this->userBusiness->listAllUsers();

        if (empty($users)) {
            return $this->json(
                [
                    'message' => 'Users list is empty.',
                ],
                200
            );
        }

        return $this->json(
            [
                'message' => 'Users listed successfuly.',
                'allUsers' => $users,
            ],
            200
        );
    }
}
