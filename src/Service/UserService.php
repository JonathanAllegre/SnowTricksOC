<?php
/**
 * Created by PhpStorm.
 * User: jonathan
 * Date: 16/06/2018
 * Time: 11:02
 */

namespace App\Service;

use App\Entity\User;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * USER SERVICES CLASS
 * Class UserServices
 * @package App\Service\User
 */
class UserService
{
    private $manager;
    private $mailer;
    private $passwordEncoder;
    private $session;

    const NO_USER_FOUND   = "Aucun utilisateur trouvé";
    const ERROR_MAILER    = "Une erreur est survenue lors de l'envoie du mail";
    const STANDARD_ERROR  = "Une erreur s'est produite.";
    const ALLREADY_ACTIVE = "Votre compte est déjà actif.";

    public function __construct(
        ObjectManager $manager,
        MailerService $mailer,
        UserPasswordEncoderInterface $encoder,
        SessionInterface $session
    ) {
        $this->manager         = $manager;
        $this->mailer          = $mailer;
        $this->passwordEncoder = $encoder;
        $this->session         = $session;
    }

    /**
     * CONFIRM THE USER ACCUNT
     * @param User $user
     * @throws \Exception
     * @return mixed
     */
    public function accuntConfirmation(User $user)
    {

        // IF USER ALREADY ACTIVE
        if ($user->getActive() === 1) {
            return self::ALLREADY_ACTIVE;
        }

        // WE VALIDATE & RENGENERATE TOKEN
        $user->setActive(1);
        $user->setToken($user->generateToken());

        //EM
        $this->manager->flush();

        return true;
    }

    /**
     * FORGOT PASSWORD SEND MAIL
     * @param $userName
     * @return mixed
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function forgotPassword($form)
    {
        $userName = $form['username'];

        $user = $this->manager->getRepository(User::class)->findOneBy(['username' => $userName]);
        // VERIF IF USER EXIST
        if (!$user) {
            return self::NO_USER_FOUND;
        }

        // WE SEND MAIL REINIT PASS
        if (!$this->mailer->sendResetPass($user)) {
            return self::ERROR_MAILER;
        }

        return true;
    }

    /**
     * REGISTER NEW USER
     * @param User $user
     * @return bool|string
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function registerUser(User $user)
    {
        // ENCODE PASSWORD
        $password = $this->passwordEncoder->encodePassword($user, $user->getPlainPassword());
        $user->setPassword($password);

        // SAVE THE USER
        $this->manager->persist($user);
        $this->manager->flush();

        // MAILER
        if (!$this->mailer->sendRegisterConfirmation($user)) {
            return self::ERROR_MAILER;
        }

        return true;
    }

    /**
     * RESET THE PASSWORD USER
     * @param User $user
     * @param $userForm
     * @throws \Exception
     */
    public function resetPassword(User $user, $userForm):void
    {

        // PASSWORD ENCODER
        $password = $this->passwordEncoder->encodePassword($user, $userForm['plainPassword']['second']);
        $user->setPassword($password);

        // REGENERATE TOKEN
        $user->setToken($user->generateToken());

        // SAVE THE NEW PASSWORD
        $this->manager->flush();
    }
}
