<?php

declare(strict_types=1);

namespace App\Security\Guard;

use App\Factory\Response\ResponseFactory;
use App\Repository\Application\ApplicationRepositoryInterface;
use App\Service\Security\Encoder\SecretEncoder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\CustomCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;

class AppAuthenticator extends AbstractAuthenticator
{
    public function __construct(
        private readonly ResponseFactory $responseFactory,
        private readonly ApplicationRepositoryInterface $applicationRepository,
        private readonly SecretEncoder $secretEncoder
    ) {
    }

    public function supports(Request $request): bool
    {
        return true;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        return $this->responseFactory->createFailureMessage(
            $request->headers->get('Accept', 'application/json'),
            strtr($exception->getMessageKey(), $exception->getMessageData()),
            Response::HTTP_UNAUTHORIZED
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey): ?Response
    {
        return null;
    }

    public function authenticate(Request $request): Passport
    {
        try {
            $credentials = $this->getCredentials($request);
        } catch (\Exception $e) {
            throw new CustomUserMessageAuthenticationException('Invalid credentials.');
        }

        return new Passport(
            new UserBadge(
                $credentials['key'],
                fn (string $key) => $this->applicationRepository->getAppByKey($key)
            ),
            new CustomCredentials(
                function ($secret, UserInterface $application) {
                    return $this->secretEncoder->validPassword($application, $secret);
                },
                $credentials['secret']
            )
        );
    }

    public function getCredentials(Request $request): array
    {
        $body = json_decode($request->getContent(), true);

        return [
            'key' => (string) ($body['data']['key'] ?? ''),
            'secret' => (string) ($body['data']['secret'] ?? ''),
        ];
    }
}
