<?php

declare(strict_types=1);

namespace App\Security\Provider;

use App\Entity\User\User;
use App\Repository\User\UserRepository;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

readonly class UserProvider implements UserProviderInterface
{
    public function __construct(protected UserRepository $userRepository, protected RequestStack $request)
    {
    }

    public function refreshUser(UserInterface $user): UserInterface
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(\sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }
        $username = $user->getUsername();

        return $this->fetchUser($username);
    }

    public function supportsClass($class): bool
    {
        return User::class === $class;
    }

    protected function fetchUser($username): User
    {
        try {
            return $this->userRepository->findUserByUserName($username);
        } catch (UserNotFoundException $exception) {
            throw $exception;
        }
    }

    public function loadUserByIdentifier(string $username): UserInterface
    {
        return $this->fetchUser($username);
    }
}
