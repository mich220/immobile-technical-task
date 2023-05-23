<?php

declare(strict_types=1);

namespace App\Repository\Category;

use App\Entity\Category\Category;

interface CategoryRepositoryInterface
{
    public function findById(int $id): ?Category;

    public function save(Category $entity): void;

    public function remove(Category $entity): void;
}
