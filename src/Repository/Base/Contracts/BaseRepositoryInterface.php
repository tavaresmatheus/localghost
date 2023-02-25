<?php

namespace App\Repository\Base\Contracts;

interface BaseRepositoryInterface
{
    public function create(object $entity): array;
    public function findById(string $entityId): ?object;
    public function listAll(): array;
    public function delete(string $entityId): bool;
}