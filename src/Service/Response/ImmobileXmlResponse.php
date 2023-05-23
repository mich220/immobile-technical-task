<?php

declare(strict_types=1);

namespace App\Service\Response;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\XmlEncoder;

class ImmobileXmlResponse extends Response
{
    public function __construct($content = '', int $status = Response::HTTP_NOT_FOUND, array $headers = [])
    {
        parent::__construct($content, $status, $headers);
    }

    public function getFormat(): string
    {
        return XmlEncoder::FORMAT;
    }
}
