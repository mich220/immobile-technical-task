<?php

declare(strict_types=1);

namespace App\Security\Handler;

use App\Entity\Api\Application;
use App\Factory\Response\ResponseFactory;
use App\Factory\Security\JWTFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;

class AuthSuccessHandler implements AuthenticationSuccessHandlerInterface
{
    public function __construct(
        private readonly JWTFactory $JWTFactory,
        private readonly ResponseFactory $responseFactory
    ) {
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token): Response
    {
        /** @var Application $app */
        $app = $token->getUser();
        if (!$app instanceof Application) {
            return $this->responseFactory->createFailureMessage(
                $request->headers->get('Accept', 'application/json'),
                'Invalid application',
                Response::HTTP_UNAUTHORIZED
            );
        }

        return $this->responseFactory->createSuccessMessage(
            $request->headers->get('Accept', 'application/json'),
            [
                'token' => $this->JWTFactory->create($app, $app->getAppKey()),
            ]
        );
    }
}
