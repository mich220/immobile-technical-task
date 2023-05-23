<?php

declare(strict_types=1);

namespace App\Repository\Application;

use App\Entity\Api\Application;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ApplicationRepository extends ServiceEntityRepository implements ApplicationRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Application::class);
    }

    public function save(Application $entity): void
    {
        $this->getEntityManager()->persist($entity);

        $this->getEntityManager()->flush();
    }

    public function remove(Application $entity): void
    {
        $this->getEntityManager()->remove($entity);

        $this->getEntityManager()->flush();
    }

    public function saveEntity(Application $entity): bool
    {
        try {
            $this->save($entity);
        } catch (\Exception $e) {
            return false;
        }

        return true;
    }

    public function getAppByKey(string $appKey): ?Application
    {
        return $this->findOneBy(['appKey' => $appKey]);
    }
}
