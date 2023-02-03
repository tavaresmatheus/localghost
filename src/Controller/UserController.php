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
        $persistanceMessage = $this->userBusiness->createUser($user);
        return $this->json(
            [
                'message' => 'User created successfuly.',
                'userInformation' => [
                    'id' => $persistanceMessage['id'],
                    'name' => $persistanceMessage['name'],
                    'e-mail' => $persistanceMessage['email'],
                    'roles' => $persistanceMessage['roles'],
                ],
            ],
            201
        );
    }

    #[Route('/users/{id}', name: 'users')]
    public function removeUser(string $id): JsonResponse
    {
        $user = $this->userBusiness->findUserById($id);

        if (empty($user)) {
            return $this->json(
                [
                    'message' => 'User does not exists.',
                ],
                422
            );
        }

        $this->userBusiness->removeUser($id);
        return $this->json(
            [
                'message' => 'User removed successfuly.',
                'userInformation' => [
                    'id' => $id,
                    'name' => $user->getName(),
                    'e-mail' => $user->getEmail(),
                    'roles' => $user->getRoles(),
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
