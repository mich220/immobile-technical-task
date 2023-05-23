<?php

declare(strict_types=1);

namespace App\Controller\Product;

use App\Controller\AbstractController;
use App\Entity\Product\Product;
use App\Factory\Response\ResponseFactory;
use App\Form\ProductType;
use App\Mapper\ProductMapper;
use App\Repository\Product\ProductRepositoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api')]
class ProductController extends AbstractController
{
    public function __construct(private readonly ResponseFactory $responseFactory)
    {
    }

    #[Route('/product/{id}', name: 'api_product_find', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function find(int $id, ProductRepositoryInterface $productRepository, Request $request): Response
    {
        /** @var Product $product */
        $product = $productRepository->findOneBy(['id' => $id]);
        if (null === $product) {
            return $this->responseFactory
                ->createFailureMessage(acceptHeader: $request->headers->get('Accept'), message: 'Product not found');
        }

        return $this->responseFactory->createSuccessMessage(
            acceptHeader: $request->headers->get('Accept'),
            message: [
                'title' => $product->getName(),
                'description' => $product->getDescription(),
            ]
        );
    }

    #[Route('/product', name: 'api_product_post', methods: ['POST'])]
    public function store(Request $request, ProductRepositoryInterface $productRepository): Response
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->submit($this->getRequestContent($request));
        if (!$form->isValid()) {
            return $this->responseFactory
                ->createFailureFormItemsMessage(acceptHeader: $request->headers->get('Accept'), form: $form);
        }
        $productRepository->save($product, true);

        return $this->responseFactory->createResponse(
            acceptHeader: $request->headers->get('Accept'),
            headers: ['Location' => $this->generateUrl('api_product_find', ['id' => $product->getId()])],
        );
    }

    #[Route('/product/{id}', name: 'api_product_put', requirements: ['id' => '\d+'], methods: ['PUT'])]
    public function update(int $id, Request $request, ProductRepositoryInterface $productRepository): Response
    {
        $product = $productRepository->findById($id);
        if (null === $product) {
            return $this->responseFactory
                ->createFailureMessage(
                    acceptHeader: $request->headers->get('Accept'),
                    message: 'Product not found',
                    status: Response::HTTP_NOT_FOUND
                );
        }
        $form = $this->createForm(ProductType::class, $product);
        $form->submit($this->getRequestContent($request));
        if (!$form->isValid()) {
            return $this->responseFactory
                ->createFailureFormItemsMessage(acceptHeader: $request->headers->get('Accept'), form: $form);
        }
        $productRepository->save($product, true);

        return $this->responseFactory->createResponse(acceptHeader: $request->headers->get('Accept'));
    }

    #[Route('/product/{id}', name: 'api_product_delete', requirements: ['id' => '\d+'], methods: ['DELETE'])]
    public function delete(int $id, ProductRepositoryInterface $productRepository, Request $request): Response
    {
        $product = $productRepository->find($id);
        if (null === $product) {
            return $this->responseFactory
                ->createFailureMessage(acceptHeader: $request->headers->get('Accept'), message: 'Product not found');
        }
        $productRepository->remove($product, true);

        return $this->responseFactory->createResponse(acceptHeader: $request->headers->get('Accept'));
    }
    
    #[Route('/product/on-sale', name: 'api_product_on_sale', methods: ['GET'])]
    public function onSale(
        Request $request,
        ProductRepositoryInterface $productRepository,
        ProductMapper $productMapper

    ): Response
    {
        return $this->responseFactory->createSuccessMessage(
            acceptHeader: $request->headers->get('Accept'),
            message: $productMapper->map($productRepository->findOnSale())
        );
    }
}
