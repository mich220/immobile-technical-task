<?php

declare(strict_types=1);

namespace App\Repository\Application;

use App\Entity\Api\Application;

interface ApplicationRepositoryInterface
{
    public function saveEntity(Application $entity): bool;

    public function getAppByKey(string $appKey): ?Application;

    public function remove(Application $entity): void;
}
