<?php

namespace App\Repository;

use App\Entity\User;

interface UserRepositoryInterface
{
    public function save(User $user): array;
    public function findById(string $userId): ?User;
    public function findByEmail(string $userEmail): ?User;
    public function update(array $user, string $userId): ?array;
    public function remove(string $userId): bool;
    public function listAll(): array;
}
