<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="new", methods={"GET", "POST"})
     * @Security("is_granted('ROLE_ADMIN')")
     * @param Request $request
     * @return Response
     */
    public function addProduct(Request $request): Response
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $product = $form->getData();
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($product);
            $entityManager->flush();
            return $this->redirect($request->getUri());
        }
        return $this->render('admin/admin.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
