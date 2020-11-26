<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\Product;
use App\services\Cart;
use Doctrine\ORM\EntityManagerInterface;
use Stripe\Checkout\Session;
use Stripe\Stripe;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StripeController extends AbstractController
{
    /**
     * @Route("/commande/create-session/{reference}", name="stripe_create_session")
     * @param EntityManagerInterface $entityManager
     * @param Cart $cart
     * @param $reference
     * @return Response
     * @throws \Stripe\Exception\ApiErrorException
     */
    public function index(EntityManagerInterface $entityManager, Cart $cart, $reference): Response
    {
        $product_for_stripe = [];
        $YOUR_DOMAIN = 'http://127.0.0.1:8000';
        $order = $entityManager->getRepository(Order::class)->findOneByReference($reference);
        //dd($order);
        if (!$order){
             new JsonResponse(['error'=>'order']);
        }
        //dd($order->getOrderDetails()->getValues());
        //Enregistrer  mes produits  OrderDetails dans la bdd
        foreach ($order->getOrderDetails()->getValues() as $product) {
            //dd($product);
            $product_object = $entityManager->getRepository(Product::class)->findOneByName($product->getProduct());
            $product_for_stripe[] = [
                'price_data' => [
                    'currency' => 'eur',
                    'unit_amount' => $product->getPrice(),
                    'product_data' => [
                        'name' => $product->getProduct(),
                        'images' => [$YOUR_DOMAIN . "/uploads/" . $product_object->getIllustration()],
                    ],
                ],
                'quantity' => $product->getQuantity(),

            ];
        }
        //dd($product_for_stripe);
        $product_for_stripe[] = [
            'price_data' => [
                'currency' => 'eur',
                'unit_amount' => $order->getCarrierPrice(),
                'product_data' => [
                    'name' => $order->getCarrierName(),
                    'images' => [$YOUR_DOMAIN],
                ],
            ],
            'quantity' =>1,

        ];
        //dd($product_for_stripe);
        //STRIPE
        Stripe::setApiKey('sk_test_51HkS8uIrTqkijeRIg0U7zSmQ8CRT9lb6Hqv0XKPCvTggWk59Yvwg4lq4pCBhAcO0IygZ9mXhtuU11slN3CmSh7Xt00EnxyK7iX');

        $checkout_session = Session::create([
            'customer_email'=> $this->getUser()->getEmail(),
            'payment_method_types' => ['card'],
            'line_items' => [[
                $product_for_stripe
            ]],
            'mode' => 'payment',
            'success_url' => $YOUR_DOMAIN . '/commande/merci/{CHECKOUT_SESSION_ID}',
            'cancel_url' => $YOUR_DOMAIN . '/commande/erreur/{CHECKOUT_SESSION_ID}',
        ]);
        // dump($checkout_session->id);
        //dd($checkout_session);
        // echo json_encode(['id' => $checkout_session->id]);

        $order->setStripeSessionId($checkout_session->id);
        $entityManager->flush();
        $response = new JsonResponse(['id'=> $checkout_session->id]);
        return $response;
    }
}
