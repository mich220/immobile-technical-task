<?php

declare(strict_types=1);

namespace App\Service\Security;

use App\Entity\Api\Application;
use App\EntityRepository\Api\ApplicationRepositoryInterface;
use App\Exception\Security\AppIdNotSetException;
use App\Exception\Security\AppKeyNotAvailableException;
use App\Exception\Security\AuthorizationHeaderNotAvailableException;
use Symfony\Component\HttpFoundation\RequestStack;

class CurrentApp
{
    private ApplicationRepositoryInterface $applicationRepository;
    private JWTUser $JWTUser;
    private RequestStack $requestStack;

    public function __construct(ApplicationRepositoryInterface $applicationRepository, JWTUser $JWTUser, RequestStack $requestStack)
    {
        $this->applicationRepository = $applicationRepository;
        $this->JWTUser = $JWTUser;
        $this->requestStack = $requestStack;
    }

    private function getJWTPayload(): array
    {
        $request = $this->requestStack->getCurrentRequest();
        if (!isset($request->headers)) {
            throw new AuthorizationHeaderNotAvailableException();
        }

        $authorization = $request->headers->get('Authorization');
        if (null === $authorization) {
            throw new AuthorizationHeaderNotAvailableException();
        }

        return $this->JWTUser->getPayload($authorization) ?? [];
    }

    public function getAppId(): int
    {
        /** @var Application $app */
        $app = $this->applicationRepository->getAppByKey($this->getAppKey(), false);
        if (null === $app) {
            throw new AppKeyNotAvailableException();
        }

        return $app->getId();
    }

    public function getAppKey(): string
    {
        $payload = $this->getJWTPayload();
        if (false === array_key_exists('app-key', $payload)) {
            throw new AppIdNotSetException();
        }

        return $payload['app-key'];
    }
}
