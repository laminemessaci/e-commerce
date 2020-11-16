<?php

namespace App\Controller;

use App\Form\ChangePasswordType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AccountPasswordController extends AbstractController
{
    private $manager;
    public function __construct(EntityManagerInterface $manager){
        $this->manager = $manager;
    }
    /**
     * @Route("/compte/modifier_mot_passe", name="account_password")
     */
    public function index(Request $request, UserPasswordEncoderInterface $encoder): Response
    {
        $notification = null;


        $user = $this->getUser();
        $form = $this->createForm(ChangePasswordType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $old_pwd = $form->get('old_password')->getData();

            if ($encoder->isPasswordValid($user, $old_pwd)){
               $new_pwd = $form->get('new_password')->getData();
             $password = $encoder->encodePassword($user, $new_pwd);

             $user->setPassword($password);
             //$this->manager->persist($user);
             $this->manager->flush();
                $notification = "Votre mot de passe a été mis à jour!";

            }else{
                $notification = "Votre mot de passe actuel n'est pas valide !";
            }
        }
        return $this->render('account/password.html.twig', [
            'form'=> $form->createView(),
            'notification'=>$notification
        ]);
    }
}
