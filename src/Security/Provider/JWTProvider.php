<?php

declare(strict_types=1);

namespace App\Security\Provider;

use App\Entity\User\User;
use App\Exception\Security\JwtTokenNotExistsException;
use App\Service\Security\JWTUser;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

readonly class JWTProvider implements UserProviderInterface
{
    public function __construct(private RequestStack $request, private JWTUser $JWTUser)
    {
    }

    public function refreshUser(UserInterface $user): UserInterface
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(\sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        return $this->fetchUser();
    }

    public function supportsClass(string $class): bool
    {
        return User::class === $class;
    }

    protected function fetchUser(): UserInterface
    {
        $currentRequest = $this->request->getCurrentRequest();
        $token = $currentRequest->headers->get('Authorization');

        $user = $this->JWTUser->getUser($token);
        if (null === $user) {
            throw new UserNotFoundException();
        }

        return $user;
    }

    public function loadUserByIdentifier(string $username): UserInterface
    {
        $currentRequest = $this->request->getCurrentRequest();
        if (!$currentRequest->headers->has('Authorization')) {
            throw new JwtTokenNotExistsException();
        }

        return $this->fetchUser();
    }
}
