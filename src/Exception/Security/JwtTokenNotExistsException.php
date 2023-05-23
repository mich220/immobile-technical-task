<?php

declare(strict_types=1);

namespace App\Exception\Security;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;

class JwtTokenNotExistsException extends UserNotFoundException
{
    public function __construct()
    {
        $message = 'JWT Token not exists';
        $code = Response::HTTP_UNAUTHORIZED;

        parent::__construct($message, $code);
    }
}
