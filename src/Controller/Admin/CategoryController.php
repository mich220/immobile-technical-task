<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\Category\Category;
use App\Form\CategoryType;
use App\Repository\Category\CategoryRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin')]
class CategoryController extends AbstractController
{
    #[Route('/category', name: 'admin_category')]
    public function index(CategoryRepositoryInterface $categoryRepository): Response
    {
        $categories = $categoryRepository->findAll();

        return $this->render('category/index.html.twig', [
            'categories' => $categories,
        ]);
    }

    #[Route('/category/create', name: 'admin_category_create', methods: ['GET', 'POST'])]
    public function create(Request $request, CategoryRepositoryInterface $categoryRepository): Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $categoryRepository->save($category, true);

            return $this->redirectToRoute('admin_category');
        }

        return $this->render('category/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/category/{id}/update', name: 'admin_category_update', methods: ['GET', 'POST'])]
    public function update(int $id, Request $request, CategoryRepositoryInterface $categoryRepository): Response
    {
        $category = $categoryRepository->find($id);
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $categoryRepository->save($category, true);

            return $this->redirectToRoute('admin_category');
        }

        return $this->render('category/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/category/{id}', name: 'admin_category_delete', methods: ['DELETE'])]
    public function delete(int $id, CategoryRepositoryInterface $categoryRepository): Response
    {
        $category = $categoryRepository->find($id);
        $categoryRepository->remove($category, true);

        return $this->redirectToRoute('admin_category');
    }
}
