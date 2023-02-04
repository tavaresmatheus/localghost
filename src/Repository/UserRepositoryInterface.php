<?php

namespace App\Repository;

use App\Entity\User;

interface UserRepositoryInterface
{
    public function save(User $user): array;
    public function remove(string $userId): bool;
    public function findById(string $userId): ?User;
    public function findByEmail(string $userEmail): ?User;
    public function listAll(): array;
}
