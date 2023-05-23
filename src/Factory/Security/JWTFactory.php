<?php

declare(strict_types=1);

namespace App\Factory\Security;

use App\Service\Security\JWT;
use Symfony\Component\Security\Core\User\UserInterface;

readonly class JWTFactory
{
    public function __construct(private JWT $JWT)
    {
    }

    public function create(UserInterface $user, string $appKey): string
    {
        return $this->JWT->encode([
            'username' => $user->getUserIdentifier(),
            'roles' => $user->getRoles(),
            'app-key' => $appKey,
        ]);
    }
}
