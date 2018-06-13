<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserResetPassType;
use App\Form\UserType;
use App\Service\User\UserFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class UserController extends Controller
{
    /**
     * @Route("/user", name="user")
     */
    public function index()
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

    /**
     * LOGIN USER
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

        return $this->render('user/login.html.twig', array('last_username' => $lastUsername, 'error' => $error,));
    }

    /**
     * REGISTER USER
     * @param Request $request
     * @param UserFactory $userFactory
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     * @Route("/register", name="user_registration")
     */
    public function register(Request $request, UserFactory $userFactory)
    {

        // BUILD THE FORM
        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        // HANDLE THE SUBMIT
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // REGISTER USER
            $register = $userFactory->createNewRegisterUser()->register($user);
        }

        if (isset($register) && true === $register) {
            $this->addFlash('success', 'Votre compte à bien été crée.');

            return $this->redirectToRoute('home');
        }

        return $this->render('user/register.html.twig', array('form' => $form->createView()));
    }

    /**
     * CONFIRM USER ACCOUNT
     * @param string $username
     * @param string $token
     * @param UserFactory $userFactory
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws \Exception
     * @Route("/register/confirmation/{username}/{token}", name="accountConfirmation")
     */
    public function accountConfirmation(string $username, string $token, UserFactory $userFactory)
    {
        // GET USER
        $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(['username' => $username]);

        // IF TOKEN NOT MATCH
        if (null === $user || $user->getToken() !== $token) {
            $this->addFlash('warning', 'Une erreur est survenue durant la validation..');

            return $this->redirectToRoute('home');
        }

        // IF USER ALREADY ACTIVE
        if ($user->getActive() === 1) {
            $this->addFlash('warning', 'Votre compte est déjà actif.');

            return $this->redirectToRoute('home');
        }

        // VALIDATION
        $userFactory->createNewAccuntConfirmation()->confirm($user);

        $this->addFlash('success', 'Votre compte est maintenant activé.');

        return $this->redirectToRoute('home');
    }


    /**
     * @param Request $request
     * @param UserFactory $userFactory
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     * @Route("forgot/password", name="forgotPassword")
     */
    public function forgotPassword(Request $request, UserFactory $userFactory)
    {
        // CHECK METHOD
        if ('POST' !== $request->getMethod()) {
            return $this->render('user/forgotPassword.html.twig');
        }

        // CHECK CSRF
        if (!$this->isCsrfTokenValid('forgotPass', $request->request->get('_csrf_token'))) {
            $this->addFlash('warning', 'Une erreur est survenue');

            return $this->render('user/forgotPassword.html.twig');
        }

        $forgotPass = $userFactory->createNewForgotPass()->forgot($request->request->get('_username'));
        if ($forgotPass['statut']) {
            $this->addFlash('success', "Un e-mail vient d'être envoyé");

            return $this->redirectToRoute('home');
        }

        $this->addFlash('warning', $forgotPass['error']);

        return $this->render('user/forgotPassword.html.twig');
    }

    /**
     * @param Request $request
     * @param string $token
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     * @Route("reset/password/{token}", name="resetPassword")
     */
    public function resetPassWord(Request $request, string $token, UserFactory $userFactory)
    {

        $user = new User();
        $form = $this->createForm(UserResetPassType::class, $user);

        $form->handleRequest($request);
        if (!$form->isSubmitted() || !$form->isValid()) {
            return $this->render('user/resetPass.html.twig', array('form' => $form->createView()));
        }

        $resetPass = $userFactory->creatNewResetPass()->reset($user, $token);
        if (false === $resetPass['statut']) {
            $this->addFlash('warning', $resetPass['error']);

            return $this->render('user/resetPass.html.twig', array('form' => $form->createView()));
        }

        $this->addFlash('success', 'Votre mot de passe à bien été réinitialiser.');

        return $this->render('user/resetPass.html.twig', array('form' => $form->createView()));
    }
}
