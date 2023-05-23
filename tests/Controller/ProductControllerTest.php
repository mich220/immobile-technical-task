<?php

declare(strict_types=1);

namespace App\Tests\Controller;

use App\Tests\Cases\BaseWebTestCase;
use App\Tests\Generator\ProductGenerator;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\HttpFoundation\Response;

class ProductControllerTest extends BaseWebTestCase
{
    #[Test]
    public function testItShowsProduct(): void
    {
        /** @var ProductGenerator $productGenerator */
        $productGenerator = $this->getService(ProductGenerator::class);
        list($id) = $productGenerator->generate(1);
        $this->makeRequest('GET', sprintf('/api/product/%s', $id));

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }
}
