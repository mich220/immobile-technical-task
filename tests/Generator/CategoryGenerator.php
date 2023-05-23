<?php

declare(strict_types=1);

namespace App\Tests\Generator;

use App\Entity\Category\Category;
use App\Repository\Category\CategoryRepository;

readonly class CategoryGenerator
{
    public function __construct(private CategoryRepository $categoryRepository)
    {
    }

    public function generate(int $count): array
    {
        $ids = [];

        for ($i = 0; $i < $count; ++$i) {
            $category = (new Category())
                ->setTitle('Category '.$i)
                ->setDescription('Description '.$i);

            $this->categoryRepository->save($category, true);
            $ids[] = $category->getId();
        }

        return $ids;
    }
}
