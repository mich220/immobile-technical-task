<?php

declare(strict_types=1);

namespace App\Service\Security\Encoder;

use App\Entity\Api\Application;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class SecretEncoder
{
    private UserPasswordHasherInterface $passwordEncoder;

    public function __construct(UserPasswordHasherInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function encodePassword(string $plainPassword): string
    {
        return $this->passwordEncoder->hashPassword(new Application(), $plainPassword);
    }

    public function validPassword(Application $application, string $plainPassword): bool
    {
        if (null === $application->getAppSecret()) {
            throw new \InvalidArgumentException('Application entity is not valid.');
        }

        return $this->passwordEncoder->isPasswordValid($application, $plainPassword);
    }
}
