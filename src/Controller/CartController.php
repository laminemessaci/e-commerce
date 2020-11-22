<?php

namespace App\Controller;

use App\Entity\Product;
use App\services\Cart;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{

    private $entityManger;

    /**
     * CartController constructor.
     * @param $entityManger
     */
    public function __construct(EntityManagerInterface $entityManger)
    {
        $this->entityManger = $entityManger;
    }


    /**
     * @Route("/mon-panier", name="cart")
     */
    public function index(Cart $cart): Response
    {

        //dd($cartComplete);
        return $this->render('cart/index.html.twig', [
            'cart'=> $cart->getFullCart(),
            'somme'=> $cart->count()
        ]);

    }


    /**
     * @Route("/cart/add/{id}", name="add_to_cart")
     */
    public function add(Cart $cart, $id): Response
    {
        $cart -> add($id);
       return $this-> redirectToRoute('cart');

    }

    /**
     * @Route("/cart/decrease/{id}", name="decrease_to_cart")
     */
    public function decrease(Cart $cart, $id): Response
    {
        $cart -> decrease($id);
        return $this-> redirectToRoute('cart');
    }

    /**
     * @Route("/cart/delete/{id}", name="delete_to_cart")
     */
    public function delete(Cart $cart, $id): Response
    {
        $cart ->delete($id);
        return $this-> redirectToRoute('cart');
    }



    /**
     * @Route("/cart/remove", name="remove_my_cart")
     */
    public function remove(Cart $cart): Response
    {
        $cart ->remove();
        return $this-> redirectToRoute('cart');
    }
}
