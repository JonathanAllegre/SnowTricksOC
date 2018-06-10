<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Service\User\UserManager;
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
     * @param Request $request
     * @param UserManager $userManager
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     * @Route("/register", name="user_registration")
     */
    public function register(Request $request, UserManager $userManager)
    {
        // BUILD THE FORM
        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        // HANDLE THE SUBMIT
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // REGISTER USER
            $register = $userManager->registerUser($user);
        }

        if (isset($register) && true === $register) {
            $this->addFlash('success', 'Votre compte à bien été crée.');

            return $this->redirectToRoute('home');
        }

        return $this->render('user/register.html.twig', array('form' => $form->createView()));
    }


    /**
     * @param string $username
     * @param string $token
     * @param UserManager $userManager
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws \Exception
     * @Route("/register/confirmation/{username}/{token}", name="accountConfirmation")
     */
    public function accountConfirmation(string $username, string $token, UserManager $userManager)
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
        $userManager->accountConfirmation($user);

        $this->addFlash('success', 'Votre compte est maintenant activé.');

        return $this->redirectToRoute('home');
    }


    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("forgot/password", name="forgotPassword")
     */
    public function forgotPassword(Request $request)
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

        /*$user = $this->getDoctrine()
            ->getRepository(User::class)
            ->findOneBy(['username' => $request->request->get('username')]);

        $this->addFlash('success', 'ok');*/
        return $this->render('user/forgotPassword.html.twig');
    }
}
