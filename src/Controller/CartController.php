<?php

namespace App\Controller;

use App\Service\CartService\CartService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{

    /**
     * @Route("/cart", name="cart")
     * @Security("is_granted('ROLE_USER')")
     * @param CartService $cartService
     * @return Response
     */
    public function showCart(CartService $cartService): Response
    {
        return $this->render('cart/cart.html.twig', [
            'items' => $cartService->getFullCart(),
            'total' => $cartService->getTotal()
        ]);
    }

    /**
     * @Route("/cart/add/{id}", name="cart add")
     * @Security("is_granted('ROLE_USER')")
     * @param $id
     * @param CartService $cartService
     * @param Request $request
     * @return Response
     */
    public function addToCart($id, CartService $cartService, Request $request): Response
    {
        $cartService->add($id);
        $referer = $request->headers->get('referer');
        return $this->redirect($referer);
    }

    /**
     * @Route("/cart/remove/{id}", name="cart remove")
     * @Security("is_granted('ROLE_USER')")
     * @param $id
     * @param CartService $cartService
     * @param Request $request
     * @return Response
     */
    public function removeFromCart($id, CartService $cartService, Request $request): Response
    {
        $cartService->remove($id);
        $referer = $request->headers->get('referer');
        return $this->redirect($referer);
    }
}
