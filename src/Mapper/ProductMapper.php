<?php

declare(strict_types=1);

namespace App\Mapper;

use App\Entity\Product\Product;

class ProductMapper
{
    public function map(array $products): array
    {
        return array_map(function (Product $product) {
            return [
                'name' => $product->getName(),
                'price' => $product->getPrice(),
                'description' => $product->getDescription(),
                'discountPrice' => $product->getDiscountPrice(),
                'discountPeriodStartsAt' => $product->getDiscountPeriodStartsAt()->format('Y-m-d H:i:s'),
                'discountPeriodEndsAt' => $product->getDiscountPeriodEndsAt()->format('Y-m-d H:i:s'),
            ];
        }, $products);
    }
}