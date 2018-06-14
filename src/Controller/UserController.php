<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserResetPassType;
use App\Form\UserType;
use App\Service\User\UserFactory;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
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
     * @Template()
     */
    public function login(AuthenticationUtils $authenticationUtils)
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return ['last_username' => $lastUsername, 'error' => $error];
    }

    /**
     * @Route("/register", name="user_registration")
     * @Template()
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

        return ['form' => $form->createView()];
    }

    /**
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
     * @Route("forgot/password", name="forgotPassword")
     * @Template()
     */
    public function forgotPassword(Request $request, UserFactory $userFactory)
    {
        // CHECK METHOD
        if ('POST' !== $request->getMethod()) {
            return [];
        }

        // CHECK CSRF
        if (!$this->isCsrfTokenValid('forgotPass', $request->request->get('_csrf_token'))) {
            $this->addFlash('warning', 'Une erreur est survenue');

            return [];
        }

        $forgotPass = $userFactory->createNewForgotPass()->forgot($request->request->get('_username'));
        if ($forgotPass['statut']) {
            $this->addFlash('success', "Un e-mail vient d'être envoyé");

            return $this->redirectToRoute('home');
        }

        $this->addFlash('warning', $forgotPass['error']);

        return [];
    }

    /**
     * @Route("reset/password/{token}", name="resetPassword")
     * @Template()
     */
    public function resetPassWord(Request $request, string $token, UserFactory $userFactory)
    {

        $user = new User();
        $form = $this->createForm(UserResetPassType::class, $user);

        $form->handleRequest($request);
        if (!$form->isSubmitted() || !$form->isValid()) {
            return ['form' => $form->createView()];
        }

        $resetPass = $userFactory->creatNewResetPass()->reset($user, $token);
        if (false === $resetPass['statut']) {
            $this->addFlash('warning', $resetPass['error']);

            return ['form' => $form->createView()];
        }

        $this->addFlash('success', 'Votre mot de passe à bien été réinitialiser.');

        return ['form' => $form->createView()];
    }
}
