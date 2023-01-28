<?php

namespace App\Controller;

use App\Businesses\UserBusiness;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    private UserRepository $userRepository;
    private UserBusiness $userBusiness;

    public function __construct(
        UserRepository $userRepository,
        UserBusiness $userBusiness
    )
    {
        $this->userRepository = $userRepository;
        $this->userBusiness = $userBusiness;
    }

    #[Route('/users', name: 'users')]
    public function index(Request $request): JsonResponse
    {
        $requestContent = $request->getContent();
        $userInformation = json_decode($requestContent, true);

        $user = new User();
        $user->setName($userInformation['name']);
        $user->setEmail($userInformation['email']);
        $user->setPassword($this->userBusiness->encryptPassword($userInformation['password']));
        $user->setRoles($userInformation['role']);
        $persistanceMessage = $this->userRepository->save($user);
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
}
