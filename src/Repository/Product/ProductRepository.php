<?php

declare(strict_types=1);

namespace App\Repository\Product;

use App\Entity\Product\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ProductRepository extends ServiceEntityRepository implements ProductRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    public function findById(int $id): ?Product
    {
        return $this->find($id);
    }

    public function save(Product $entity): void
    {
        $this->getEntityManager()->persist($entity);

        $this->getEntityManager()->flush();
    }

    public function remove(Product $entity): void
    {
        $this->getEntityManager()->remove($entity);

        $this->getEntityManager()->flush();
    }

    public function findOnSale(): ?array
    {
        $qb = $this->createQueryBuilder('p');
        $now = new \DateTime();

        $qb->andWhere(':now >= p.discount_period_starts_at')
            ->andWhere(':now <= p.discount_period_ends_at')
            ->setParameter('now', $now)
            ->orderBy('p.name', 'ASC');

        return $qb->getQuery()->getResult();
    }
}
