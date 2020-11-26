<?php

namespace App\Controller;

use App\Entity\Order;
use App\services\Cart;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderSuccessController extends AbstractController
{
    private $entityManager;

    /**
     * OrderSuccessController constructor.
     * @param $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }


    /**
     * @Route("/commande/merci/{stripeSessionId}", name="order_success")
     */
    public function index($stripeSessionId, Cart $cart): Response
    {
        $order = $this->entityManager->getRepository(Order::class)->findOneByStripeSessionId($stripeSessionId);
        //dd($order);
        if (!$order || $order->getUser() != $this->getUser()) {
            return $this->redirectToRoute('home');
        }

        //vider le panier
        $cart->remove();
        //Modifier le statut 'isPaid' a  1
        if (!$order->getIsPaid()){
            $order->setIsPaid(1);
            $this->entityManager->flush();
        }
            //Envoyer un email pour confirmer la commande

            //Afficher les information au user
            return $this->render('order_success/index.html.twig', [
                'order' => $order
            ]);
    }
}
