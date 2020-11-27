<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterType;
use App\services\MailJet;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class RegisterController extends AbstractController
{
    private $manager;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @Route("/inscription", name="register")
     * @param Request $request
     * @return Response
     */
    public function index(Request $request, UserPasswordEncoderInterface $encoder): Response
    {
        $user = new User();
        $notification = null;

        $form = $this->createForm(RegisterType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();

            $search_user = $this->manager->getRepository(User::class)->findOneByEmail($user->getEmail());

            if (!$search_user) {
                $passwordCrypte = $encoder->encodePassword($user, $user->getPassword());
                $user->setPassword($passwordCrypte);
                $this->manager->persist($user);
                $this->manager->flush();
                $notification = 'Votre inscription a été prise en compte, vous pouvez dés à present vous connecter, pour vous connecter cliquez ';
                $mail = new MailJet();
                $content = 'Bienvenu '.$user->getFullName().' dans notre boutique en ligne. <br/> Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrystandard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged';
                $mail->send($user->getEmail(), $user->getFirstname(), "Bienvenu dans la boutique made in france !", $content );
            } else {
                $notification = "L'email que vous avez renseigné existe deja !,pour vous connecter cliquez ";
            }

            //return $this->redirectToroute('account');
        }

        return $this->render('register/index.html.twig', [
            'form' => $form->createView(),
            'notification' => $notification
        ]);
    }
}
