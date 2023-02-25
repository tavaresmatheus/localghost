<?php

namespace App\Repository;

use App\Entity\User;
use App\Repository\Base\BaseRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

class UserRepository extends BaseRepository implements PasswordUpgraderInterface, UserRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct(
            $registry,
            User::class
        );
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
