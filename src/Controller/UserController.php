<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserForgotPassType;
use App\Form\UserResetPassType;
use App\Form\UserType;
use App\Service\UserService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class UserController extends Controller
{
    /**
     * @Route("/user")
     */
    public function index()
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

    /**
     * @Route("/login")
     * @Template()
     */
    public function login(AuthenticationUtils $authenticationUtils)
    {

        // IF THE USER IS ALREADY CONNECT WE REDIRECT TO HOME
        if ($this->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirectToRoute('app_home_index');
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return ['last_username' => $lastUsername, 'error' => $error];
    }

    /**
     * @Route("/register")
     * @Template()
     */
    public function register(Request $request, UserService $userServices)
    {

        // BUILD THE FORM
        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        // HANDLE THE SUBMIT
        $form->handleRequest($request);
        if (!$form->isSubmitted() || !$form->isValid()) {
            return ['form' => $form->createView()];
        }

        // REGISTER SERVICES
        $register = $userServices->registerUser($user);
        // IF REGISTER IS TRUE
        if (true === $register) {
            $this->addFlash('success', 'Votre compte à bien été crée.');

            return $this->redirectToRoute('app_home_index');
        }
        // IF REGISTER IS FALSE
        $this->addFlash('warning', $register);

        return ['form' => $form->createView()];
    }

    /**
     * @Route("/register/confirmation/{username}/{token}")
     */
    public function accountConfirmation(User $user, UserService $userServices)
    {
        // VALIDATION
        $accuntConfirmation = $userServices->accuntConfirmation($user);
        if (true === $accuntConfirmation) {
            $this->addFlash('success', 'Votre compte est maintenant activé.');

            return $this->redirectToRoute('app_home_index');
        }

        $this->addFlash('warning', $accuntConfirmation);

        return $this->redirectToRoute('app_home_index');
    }


    /**
     * @Route("forgot/password")
     * @Template()
     */
    public function forgotPassword(Request $request, UserService $userServices)
    {
        $form = $this->createForm(UserForgotPassType::class);

        $form->handleRequest($request);
        if (!$form->isSubmitted() || !$form->isValid()) {
            return ['form' => $form->createView()];
        }

        // FORGOT PASS SERVICES
        $forgotPass = $userServices->forgotPassword($request->request->get('user_forgot_pass'));
        if (true === $forgotPass) {
            $this->addFlash('success', "Un e-mail vient d'être envoyé");

            return $this->redirectToRoute('app_home_index');
        }

        $this->addFlash('warning', $forgotPass);

        return ['form' => $form->createView()];
    }

    /**
     * @Route("reset/password/{token}")
     * @Template()
     */
    public function resetPassWord(Request $request, User $user, UserService $userServices)
    {
        $form = $this->createForm(UserResetPassType::class);

        $form->handleRequest($request);
        if (!$form->isSubmitted() || !$form->isValid()) {
            return ['form' => $form->createView()];
        }

        // RESET PASS FORM DATA
        $userResetPass = $request->request->get('user_reset_pass');
        $userServices->resetPassword($user, $userResetPass);

        $this->addFlash('success', 'Votre mot de passe à bien été réinitialiser.');
        return $this->redirectToRoute('app_home_index');
    }
}
