<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{

    /**
     * @Route("/", name="main", methods={"GET"})
     * @param ProductRepository $productRepository
     * @return Response
     */
    public function showMainPage(ProductRepository $productRepository): Response
    {
        $products = $productRepository->findMostExpensive(6);

        return $this->render('main/index.html.twig', [
            'products' => $products
        ]);
    }

    /**
     * @Route("/about", name="about", methods={"GET"})
     */
    public function showContactData(): Response
    {
        return $this->render('main/about.html.twig');
    }
}
