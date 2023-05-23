<?php

declare(strict_types=1);

namespace App\Security\Provider;

use App\Entity\Api\Application;
use App\Repository\Application\ApplicationRepositoryInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

readonly class AppProvider implements UserProviderInterface
{
    public function __construct(private ApplicationRepositoryInterface $applicationRepository)
    {
    }

    protected function fetchUser(string $key): ?UserInterface
    {
        try {
            return $this->applicationRepository->getAppByKey($key);
        } catch (UserNotFoundException $exception) {
            throw $exception;
        }
    }

    public function refreshUser(UserInterface $user): UserInterface
    {
        if (!$user instanceof Application) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', get_class($user)));
        }
        $key = $user->getAppKey();

        return $this->fetchUser($key);
    }

    public function supportsClass(string $class): bool
    {
        return Application::class === $class;
    }

    public function loadUserByIdentifier(string $identifier): UserInterface
    {
        return $this->fetchUser($identifier);
    }
}
