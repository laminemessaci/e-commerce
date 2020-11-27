<?php

namespace App\Controller;

use App\Entity\Order;
use App\services\Cart;
use App\services\MailJet;
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


        //Modifier le statut 'isPaid' a  1
        if ($order->getState() == 0){
            //vider le panier
            $cart->remove();
            $order->setState(1);
            $this->entityManager->flush();
        }
            //Envoyer un email pour confirmer la commande
        $mail = new MailJet();
        $content = 'Bienvenu '.$order->getUser()->getFirstname().' dans notre boutique en ligne. <br/> Marci pour votre commande! à bientôt !';
        $mail->send($order->getUser()->getEmail(), $order->getUser()->getFirstname(), "Votre commande boutique de aya est bien validée!", $content );

            //Afficher les information au user
            return $this->render('order_success/index.html.twig', [
                'order' => $order
            ]);
    }
}
