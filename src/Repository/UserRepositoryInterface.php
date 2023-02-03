<?php

namespace App\Repository;

use App\Entity\User;

interface UserRepositoryInterface
{
    public function save(User $user): array;
    public function remove(string $userId): string;
    public function findById(string $userId): ?User;
    public function listAll(): array;
}