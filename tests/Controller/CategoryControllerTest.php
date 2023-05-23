<?php

declare(strict_types=1);

namespace App\Tests\Controller;

use App\Tests\Cases\BaseWebTestCase;
use App\Tests\Generator\CategoryGenerator;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\HttpFoundation\Response;

class CategoryControllerTest extends BaseWebTestCase
{
    #[Test]
    public function testItShowCategory(): void
    {
        /** @var CategoryGenerator $categoryGenerator */
        $categoryGenerator = $this->getService(CategoryGenerator::class);
        list($id) = $categoryGenerator->generate(1);
        $this->makeRequest('GET', sprintf('/api/category/%s', $id));

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }
}
