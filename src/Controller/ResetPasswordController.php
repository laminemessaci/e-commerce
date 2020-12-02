<?php

namespace App\Controller;

use App\Entity\ResetPassword;
use App\Entity\User;
use App\Form\ResetPasswordType;
use App\services\MailJet;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ResetPasswordController extends AbstractController
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
     * @Route("/mot-de-passe-oublier", name="reset_password")
     */
    public function index(Request $request): Response
    {
        //Si deja connecter on redirige vers home
        if ($this->getUser()) {
            return $this->redirectToRoute('home');
        }
        if ($request->get('email')) {
            //dd($request->get('email'));
            $user = $this->entityManger->getRepository(User::class)->findOneByEmail($request->get('email'));
            //dd($user);
            if ($user) {
                // 1.Stocker en base de donnée la demande de reset_password avec le user associé , token, createdAt.
                $reset_password = new ResetPassword();
                $reset_password->setUser($user);
                $reset_password->setToken(uniqid());
                $reset_password->setCreatedAt(new \DateTime());
                $this->entityManger->persist($reset_password);
                $this->entityManger->flush();
                // 2.Envoyer un email a l utilisateur avec un lien lui permettant de mettre à jour le mot de passe

                $url = $this->generateUrl('update_password', [
                    'token' => $reset_password->getToken()
                ]);

                $content = "Bonjour " . $user->getFullName() . " </br> Vous avez demandé à réinitialiser votre mot de passe sur la Boutique de Aya.</br></br>";
                $content .= "Merci de bien vouloir cliquer sur le lien suivant pour <a href='.$url.'>mettre à jour votre mot de passe</a>  </br>";
                $mail = new MailJet();
                $mail->send($user->getEmail(), $user->getFullName(), 'Réinitialiser votre mot de passe sur la Boutique de Aya', $content);

                $this->addFlash('notice', 'Vous allez recevoir dans quelque secondes un mail avec la procédure pour réinitialiser votre mot de passe');
            } else {
                $this->addFlash('notice', 'Cette adresse email est inconnue.');
            }
        }
        return $this->render('reset_password/index.html.twig');
    }

    /**
     * @Route("/modifier-mon-mot-de-passe/{token}", name="update_password")
     */
    public function update(UserPasswordEncoderInterface  $encoder, Request $request, $token): Response
    {
        //dd($token);
        // On va chercher le user concerner par son token
        $reset_password = $this->entityManger->getRepository(ResetPassword::class)->findOneByToken($token);
        if (!$reset_password) {
            return $this->redirectToRoute('reset_password');
        }
        // Vérifier si le createdAt = Now - 3h for exemple
        $now = new \DateTime();
        if ($now > $reset_password->getCreatedAt()->modify('+ 2 hour')) {

            $this->addFlash('notice', 'Votre mot de passe a expiré. Merci de renouvler l\'opération');
            return $this->redirectToRoute('reset_password');
        }
        //rendre une vue avec mot de passe et confirmation de ce dernier
        $form = $this->createForm(ResetPasswordType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $new_passwor = $form->get('new_password')->getData();
            //dd($new_passwor);
            //Encodage du mot de passe
            $password_encoder = $encoder->encodePassword($reset_password->getUser(), $new_passwor);
            $reset_password->getUser()->setPassword($password_encoder);
            //flush en BDD
            $this->entityManger->flush();
            //redirection de l'utilisateur vers la page de connexion
            $this->addFlash('notice', "Votre mot de passe a été mise à jour");
            return $this -> redirectToRoute('app_login');
        }

        return $this->render('reset_password/update.html.twig', [
            'form' => $form->createView(),
        ]);


        //dd($reset_password);
    }
}
