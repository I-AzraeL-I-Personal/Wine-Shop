<?php

namespace App\Controller;

use App\Entity\Address;
use App\Entity\Order;
use App\Entity\OrderItem;
use App\Entity\OrderStatus;
use App\Entity\User;
use App\Form\AddressType;
use App\Repository\OrderStatusRepository;
use App\Repository\UserRepository;
use App\Service\CartService\CartService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends AbstractController
{
    /**
     * @Route("/order-new", name="new order")
     * @Security("is_granted('ROLE_USER')")
     * @param Request $request
     * @param CartService $cartService
     * @param UserRepository $userRepository
     * @param OrderStatusRepository $orderStatusRepository
     * @return Response
     */
    public function makeOrder(Request $request, CartService $cartService, UserRepository $userRepository, OrderStatusRepository $orderStatusRepository): Response
    {
        $userName = $this->getUser()->getUsername();
        $user = $userRepository->findOneBy(['email' => $userName]);
        $address = $user->getAddress();

        $displayAddress = new Address();
        $displayAddress->setCity($address->getCity());
        $displayAddress->setStreet($address->getStreet());
        $displayAddress->setZipCode($address->getZipCode());
        $form = $this->createForm(AddressType::class, $displayAddress);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $order = new Order();
            $order->setUser($user);

            $city = $form->get('city')->getData();
            $street = $form->get('street')->getData();
            $zipCode = $form->get('zipCode')->getData();
            if ($address->getCity() == $city && $address->getStreet() == $street && $address->getZipCode() == $zipCode) {
                $order->setAddress($address);
            } else {
                $order->setAddress($displayAddress);
            }
            $cartWithData = $cartService->getFullCart();
            foreach ($cartWithData as list('product' => $product, 'quantity' => $quantity)) {
                $orderItem = new OrderItem();
                $orderItem->setQuantity($quantity);
                $orderItem->setOrder($order);
                $orderItem->setProduct($product);
                $order->addOrderItem($orderItem);
            }

            $order->setDiscount(0);
            $order->setTotalCost($cartService->getTotal());
            $order->setOrderDate(date_create(null));
            $status = $orderStatusRepository->find(1);
            $order->setStatus($status);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($order);
            $entityManager->flush();
            $cartService->removeAll();
            return $this->redirectToRoute('order');
        }

        return $this->render('order/order-new.html.twig', [
            'items' => $cartService->getFullCart(),
            'total' => $cartService->getTotal(),
            'addressForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/order", name="order", methods={"GET"})
     * @Security("is_granted('ROLE_USER')")
     * @param UserRepository $userRepository
     * @return Response
     */
    public function showOrders(UserRepository $userRepository)
    {
        $userName = $this->getUser()->getUsername();
        $user = $userRepository->findOneBy(['email' => $userName]);
        $orders = $user->getOrders();

        return $this->render('order/order.html.twig', [
            'orders' => $orders,
        ]);
    }
}
