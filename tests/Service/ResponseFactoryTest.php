<?php

declare(strict_types=1);

namespace App\Tests\Service;

use App\Factory\Response\ResponseFactory;
use App\Service\Response\ImmobileJsonResponse;
use App\Service\Response\ImmobileXmlResponse;
use App\Tests\Cases\BaseWebTestCase;
use PHPUnit\Framework\Attributes\Test;

class ResponseFactoryTest extends BaseWebTestCase
{
    #[Test]
    public function itCanCreateXmlResponse(): void
    {
        /** @var ResponseFactory $responseFactory */
        $responseFactory = $this->getService(ResponseFactory::class);

        $response = $responseFactory->createResponse('application/xml');
        $this->assertInstanceOf(ImmobileXmlResponse::class, $response);
    }

    #[Test]
    public function itCanCreateJsonResponse(): void
    {
        /** @var ResponseFactory $responseFactory */
        $responseFactory = $this->getService(ResponseFactory::class);

        $response = $responseFactory->createResponse('application/json');
        $this->assertInstanceOf(ImmobileJsonResponse::class, $response);
    }
}
