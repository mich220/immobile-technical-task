<?php

declare(strict_types=1);

namespace App\Tests\Generator;

use App\Entity\Product\Product;
use App\Repository\Category\CategoryRepository;
use App\Repository\Product\ProductRepository;

readonly class ProductGenerator
{
    public function __construct(
        private ProductRepository $productRepository,
        private CategoryRepository $categoryRepository
    ) {
    }

    public function generate(int $count, array $categoryIds = []): array
    {
        $ids = [];

        for ($i = 0; $i < $count; ++$i) {
            $product = (new Product())
                ->setName('Product '.$i)
                ->setDescription('Description '.$i)
                ->setPrice($i);

            if (rand(0, 1)) {
                $product
                    ->setDiscountPrice($i)
                    ->setDiscountPeriodStartsAt(new \DateTimeImmutable())
                    ->setDiscountPeriodEndsAt(new \DateTimeImmutable('+1 day'));
            }

            if (!empty($categoryIds)) {
                $product->addCategory($this->categoryRepository->find($categoryIds[array_rand($categoryIds)]));
            }

            $this->productRepository->save($product, true);
            $ids[] = $product->getId();
        }

        return $ids;
    }
}
