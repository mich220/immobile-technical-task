<?php

declare(strict_types=1);

namespace App\Security\Guard;

use App\Factory\Response\ResponseFactory;
use App\Service\Response\ImmobileJsonResponse;
use App\Service\Security\JWTUser;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;

class TokenAuthenticator extends AbstractAuthenticator
{
    public function __construct(
        private readonly ResponseFactory $responseFactory,
        private readonly JWTUser $JWTUser,
    ) {
    }

    public function start(Request $request, AuthenticationException $authException = null): ImmobileJsonResponse
    {
        return $this->responseFactory->createFailureMessage(
            $request->headers->get('Accept', 'application/json'),
            'Authentication required',
            Response::HTTP_FORBIDDEN
        );
    }

    public function supports(Request $request): bool
    {
        return $request->headers->has('Authorization');
    }

    public function getCredentials(Request $request): array
    {
        return [
            'jwt_token' => $request->headers->get('Authorization', ''),
            'route_name' => $request->attributes->get('_route'),
        ];
    }

    public function getUser($credentials): ?UserInterface
    {
        return $this->JWTUser->getUser($credentials['jwt_token']) ?? throw new UserNotFoundException();
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): Response
    {
        return $this->responseFactory->createFailureMessage(
            $request->headers->get('Accept', 'application/json'),
            'Authentication required',
            Response::HTTP_FORBIDDEN
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
            $user = $this->getUser($credentials);
        } catch (\Exception $exception) {
            throw new CustomUserMessageAuthenticationException('Invalid credentials.');
        }

        return new SelfValidatingPassport(new UserBadge($user->getUserIdentifier()));
    }
}
