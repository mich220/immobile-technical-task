<?php

declare(strict_types=1);

namespace App\Service\Security;

use App\Repository\Application\ApplicationRepositoryInterface;
use App\Repository\User\UserRepository;
use Symfony\Component\Security\Core\User\UserInterface;

readonly class JWTUser
{
    public function __construct(
        private JWT $JWT,
        private UserRepository $userRepository,
        private ApplicationRepositoryInterface $applicationRepository)
    {
    }

    public function getPayload(string $authHeader): ?array
    {
        $jwtToken = $this->getTokenFromAuthHeader($authHeader);
        if (empty($jwtToken)) {
            return null;
        }

        try {
            $payload = $this->JWT->decode($jwtToken);
        } catch (\Exception $exception) {
            return null;
        }

        return $payload;
    }

    private function getTokenFromAuthHeader(string $authHeader): ?string
    {
        if (empty($authHeader) || !preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
            return null;
        }

        if (!isset($matches[1])) {
            return null;
        }

        return $matches[1];
    }

    public function getUser(string $authHeader): ?UserInterface
    {
        $payload = $this->getPayload($authHeader);
        if (null === $payload) {
            return null;
        }

        if (!array_key_exists('username', $payload) || !array_key_exists('roles', $payload)) {
            return null;
        }
        if ('ROLE_APP' === $payload['roles'][0]) {
            return $this->applicationRepository->getAppByKey($payload['username']);
        }

        return $this->userRepository->getUserByUsernameFromToken($payload['username']);
    }
}
