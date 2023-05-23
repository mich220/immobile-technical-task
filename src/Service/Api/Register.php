<?php

declare(strict_types=1);

namespace App\Service\Api;

use App\Entity\Api\Application;
use App\Repository\Application\ApplicationRepositoryInterface;
use App\Service\Security\Encoder\SecretEncoder;

readonly class Register
{
    public function __construct(
        private SecretEncoder $secretEncoder,
        private ApplicationRepositoryInterface $applicationRepository
    ) {
    }

    public function createApplication(string $name, string $secret, string $key = null, int $userId = null): ?Application
    {
        $app = new Application();
        $app->setName($name);
        $app->setAppSecret($this->secretEncoder->encodePassword($secret));
        $app->setAppKey($key ?? substr(md5($name.$secret.time()), 1, 15));
        $app->setUserId($userId ?? null);

        if (false === $this->applicationRepository->saveEntity($app)) {
            return null;
        }

        return $app;
    }
}
