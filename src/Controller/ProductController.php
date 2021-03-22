<?php

namespace App\Controller;

use App\Form\ProductSearchType;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    /**
     * @Route("/product", name="product")
     * @param Request $request
     * @param ProductRepository $productRepository
     * @return Response
     */
    public function showProducts(Request $request, ProductRepository $productRepository): Response
    {
        $form = $this->createForm(ProductSearchType::class, null, [
            'action' => $this->generateUrl('product'),
            'method' => 'GET',
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $colorCategories = $form->get('color')->getData();
            $tasteCategories = $form->get('taste')->getData();

            $color = $colorCategories->getName();
            $taste = $tasteCategories->getName();
            $max = $form->get('max')->getData();

            $products = $productRepository->findFiltered($color, $taste, $max);

            return $this->render('product/product.html.twig', [
                'filterForm' => $form->createView(),
                'products' => $products
            ]);
        }

        $products = $productRepository->findAll();
        return $this->render('product/product.html.twig', [
            'filterForm' => $form->createView(),
            'products' => $products
        ]);
    }

}
