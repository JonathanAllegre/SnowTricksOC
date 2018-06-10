<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Service\Mailer\Mailer;
use App\Service\Security\UserChecker;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends Controller
{
    /**
     * @Route("/security", name="security")
     */
    public function index()
    {
        return $this->render('security/index.html.twig', [
            'controller_name' => 'SecurityController',
        ]);
    }

    /**
     * @Route("/login", name="login")
     * @param AuthenticationUtils $authenticationUtils
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function login(AuthenticationUtils $authenticationUtils)
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', array('last_username' => $lastUsername, 'error' => $error,));
    }

    /**
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     * @Route("/register", name="user_registration")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, Mailer $mailer)
    {
        // BUILD THE FORM
        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        // HANDLE THE SUBMIT
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // ENCODE PASSWORD
            $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);

            // SAVE THE USER
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            // MAILER
            $mailer->sendRegisterConfirmation($user);

            //FLASH
            $this->addFlash('success', 'Votre compte à bien été crée.');

            return $this->redirectToRoute('home');
        }

        return $this->render('security/register.html.twig', array('form' => $form->createView()));
    }

    /**
     * @param string $username
     * @param string $token
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws \Exception
     * @Route("/register/confirmation/{username}/{token}", name="accountConfirmation")
     */
    public function accountConfirmation(string $username, string $token)
    {

        // GET USER
        $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(['username' => $username]);

        if (null === $user || $user->getToken() !== $token) {
            $this->addFlash('warning', 'Une erreur est survenue durant la vérification..');

            return $this->redirectToRoute('home');
        }

        if ($user->getActive() === 1) {
            $this->addFlash('warning', 'Votre compte est déjà actif.');

            return $this->redirectToRoute('home');
        }

        // WE VALIDATE & RENGENERATE TOKEN
        $user->setActive(1);
        $user->setToken($user->generateToken());

        //$em->persist($user);
        // EM
        $this->getDoctrine()->getManager()->flush();
        $this->addFlash('success', 'Votre compte est maintenant activé.');

        return $this->redirectToRoute('home');
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     * @Route("forgot/password", name="forgotPassword")
     */
    public function forgotPassword(UserType $userType)
    {


        return $this->render('security/forgotPassword.html.twig', [

        ]);
    }
}
