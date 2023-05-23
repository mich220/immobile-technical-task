<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\Product\Product;
use App\Form\ProductType;
use App\Repository\Product\ProductRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    #[Route('/admin/product', name: 'admin_product')]
    public function index(ProductRepositoryInterface $productRepository): Response
    {
        $products = $productRepository->findAll();

        return $this->render('product/index.html.twig', ['products' => $products]);
    }

    #[Route('/product', name: 'admin_product_create', methods: ['GET', 'POST'])]
    public function create(Request $request, ProductRepositoryInterface $productRepository): Response
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $productRepository->save($product, true);

            return $this->redirectToRoute('admin_product');
        }

        return $this->render('product/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/product/{id}', name: 'admin_product_update', methods: ['GET', 'POST'])]
    public function update(int $id, Request $request, ProductRepositoryInterface $productRepository): Response
    {
        $product = $productRepository->find($id);
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $productRepository->save($product, true);

            return $this->redirectToRoute('admin_product');
        }

        return $this->render('product/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/product/{id}', name: 'admin_product_delete', methods: ['DELETE'])]
    public function delete(int $id, ProductRepositoryInterface $productRepository): Response
    {
        $product = $productRepository->find($id);
        $productRepository->remove($product, true);

        return $this->redirectToRoute('admin_product');
    }
}
