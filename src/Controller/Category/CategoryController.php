<?php

declare(strict_types=1);

namespace App\Controller\Category;

use App\Controller\AbstractController;
use App\Entity\Category\Category;
use App\Factory\Response\ResponseFactory;
use App\Form\CategoryType;
use App\Repository\Category\CategoryRepositoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api')]
class CategoryController extends AbstractController
{
    public function __construct(private readonly ResponseFactory $responseFactory)
    {
    }

    #[Route('/category/{id}', name: 'api_category_find', methods: ['GET'])]
    public function find(int $id, CategoryRepositoryInterface $categoryRepository, Request $request): Response
    {
        /** @var Category $category */
        $category = $categoryRepository->findOneBy(['id' => $id]);
        if (null === $category) {
            return $this->responseFactory
                ->createFailureMessage(acceptHeader: $request->headers->get('Accept'), message: 'Category not found');
        }

        return $this->responseFactory->createSuccessMessage(
            acceptHeader: $request->headers->get('Accept'),
            message: [
                'title' => $category->getTitle(),
                'description' => $category->getDescription(),
            ]
        );
    }

    #[Route('/category', name: 'api_category_post', methods: ['POST'])]
    public function store(Request $request, CategoryRepositoryInterface $categoryRepository): Response
    {
        $data = json_decode($request->getContent(), true);
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->submit($data);

        if (!$form->isValid()) {
            return $this->responseFactory
                ->createFailureFormItemsMessage(acceptHeader: $request->headers->get('Accept'), form: $form);
        }

        $categoryRepository->save($category);

        return $this->responseFactory->createResponse(
            acceptHeader: $request->headers->get('Accept'),
            headers: ['Location' => $this->generateUrl('api_category_find', ['id' => $category->getId()])],
        );
    }

    #[Route('/category/{id}', name: 'api_category_put', methods: ['PUT'])]
    public function update(int $id, Request $request, CategoryRepositoryInterface $categoryRepository): Response
    {
        $category = $categoryRepository->findById($id);
        if (null === $category) {
            return $this->responseFactory
                ->createFailureMessage(
                    acceptHeader: $request->headers->get('Accept'),
                    message: 'Category not found',
                    status: Response::HTTP_NOT_FOUND
                );
        }
        $form = $this->createForm(CategoryType::class, $category);
        $form->submit($this->getRequestContent($request));

        if (!$form->isValid()) {
            return $this->responseFactory
                ->createFailureFormItemsMessage(acceptHeader: $request->headers->get('Accept'), form: $form);
        }

        $categoryRepository->save($category, true);

        return $this->responseFactory->createResponse(acceptHeader: $request->headers->get('Accept'));
    }

    #[Route('/category/{id}', name: 'api_category_delete', methods: ['DELETE'])]
    public function delete(int $id, CategoryRepositoryInterface $categoryRepository, Request $request): Response
    {
        $category = $categoryRepository->find($id);
        if (null === $category) {
            return $this->responseFactory
                ->createFailureMessage(acceptHeader: $request->headers->get('Accept'), message: 'Category not found');
        }
        $categoryRepository->remove($category, true);

        return $this->responseFactory->createResponse(acceptHeader: $request->headers->get('Accept'));
    }
}
