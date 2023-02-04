<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface, UserRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct(
            $registry,
            User::class
        );
    }

    public function save(User $user): array
    {
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();

        return [
            'id' => $user->getId(),
            'name' => $user->getName(),
            'email' => $user->getEmail(),
            'roles' => $user->getRoles(),
        ];
    }

    public function remove(string $userId): bool
    {
        $user = $this->findById($userId);
        $this->getEntityManager()->remove($user);
        $this->getEntityManager()->flush();

        return true;
    }

    public function findById(string $userId): ?User
    {
        $user = new User();
        $userClassName = get_class($user);
        $userFound = $this->getEntityManager()->find($userClassName, $userId);

        return $userFound;
    }

    public function findByEmail(string $userEmail): ?User
    {
        $user = new User();
        $userClassName = get_class($user);
        $userFound = $this->getEntityManager()
            ->getRepository($userClassName)
            ->findOneBy(
                [
                    'email' => $userEmail
                ]
            );

        return $userFound;
    }

    public function listAll(): array
    {
        $user = new User();
        $userClassName = get_class($user);
        $users = $this->getEntityManager()->getRepository($userClassName);
        $usersList = $users->findAll();

        return $usersList;
    }

    public function upgradePassword(
        PasswordAuthenticatedUserInterface $user,
        string $newHashedPassword
    ): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newHashedPassword);

        $this->save($user, true);
    }
}
