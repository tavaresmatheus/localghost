<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserController extends AbstractController
{
    private $userRepository;
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    #[Route('/users', name: 'users')]
    public function index(Request $request, UserPasswordHasherInterface $passwordHasher): JsonResponse
    {
        $requestContent = $request->getContent();
        $userInformation = json_decode($requestContent, true);

        $user = new User();
        $user->setName($userInformation['name']);
        $user->setEmail($userInformation['email']);
        $user->setPassword($userInformation['password']);
        $persistanceMessage = $this->userRepository->create($user);
        return $this->json(
            [
                'message' => 'User created successfuly.',
                'userInformation' => [
                    'id' => $persistanceMessage['id'],
                    'name' => $persistanceMessage['name'],
                    'e-mail' => $persistanceMessage['email'],
                ],
            ],
            201
        );
    }
}
