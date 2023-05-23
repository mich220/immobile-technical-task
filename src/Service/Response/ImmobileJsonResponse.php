<?php

declare(strict_types=1);

namespace App\Service\Response;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncoder;

class ImmobileJsonResponse extends JsonResponse
{
    public function __construct($content = '', int $status = Response::HTTP_NOT_FOUND, array $headers = [], bool $json = false)
    {
        parent::__construct($content, $status, $headers, $json);
    }

    public function getFormat(): string
    {
        return JsonEncoder::FORMAT;
    }
}
