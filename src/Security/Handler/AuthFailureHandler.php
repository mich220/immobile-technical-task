<?php

declare(strict_types=1);

namespace App\Security\Handler;

use App\Factory\Response\ResponseFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface;

class AuthFailureHandler implements AuthenticationFailureHandlerInterface
{
    public function __construct(private readonly ResponseFactory $responseFactory)
    {
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): Response
    {
        return $this->responseFactory->createFailureMessage(
            $request->headers->get('Accept', 'application/json'),
            'Invalid credentials',
            Response::HTTP_UNAUTHORIZED
        );
    }
}
