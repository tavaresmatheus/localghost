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
        $businessResponse = $this->userBusiness->createUser($user);

        if (key_exists('errors', $businessResponse)) {
            return $this->json(
                [
                    'message' => 'Could not create the user.',
                    'errors' => $businessResponse['errors'],
                ],
                422
            );
        }

        return $this->json(
            [
                'message' => 'User created successfuly.',
                'userInformation' => [
                    'id' => $businessResponse['id'],
                    'name' => $businessResponse['name'],
                    'e-mail' => $businessResponse['email'],
                    'roles' => $businessResponse['roles'],
                ],
            ],
            201
        );
    }

    #[Route('/users/{id}', name: 'users')]
    public function removeUser(string $id): JsonResponse
    {
        $businessResponse = $this->userBusiness->removeUser($id);

        if (empty($businessResponse)) {
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
                    'id' => $businessResponse['id'],
                    'name' => $businessResponse['name'],
                    'e-mail' => $businessResponse['email'],
                    'roles' => $businessResponse['roles'],
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

    public function updateUser(
        Request $request,
        string $id
    ): JsonResponse
    {
        $requestContent = $request->getContent();
        $userInformation = json_decode($requestContent, true);
        $businessResponse = $this->userBusiness->updateUser(
            $userInformation,
            $id
        );

        if (key_exists('errors', $businessResponse)) {
            return $this->json(
                [
                    'message' => 'Could not update the user.',
                    'errors' => $businessResponse['errors'],
                ],
                422
            );
        }

        return $this->json(
            [
                'message' => 'User updated successfuly.',
                'user' => $businessResponse,
            ],
            200
        );
    }
}
