<?php

declare(strict_types=1);

namespace App\Repository\Product;

use App\Entity\Product\Product;

interface ProductRepositoryInterface
{
    public function findById(int $id): ?Product;

    public function findOnSale(): ?array;
    public function save(Product $entity): void;

    public function remove(Product $entity): void;
}
