<?php
/**
 * Created by PhpStorm.
 * User: jonathan
 * Date: 16/06/2018
 * Time: 11:02
 */

namespace App\Service\User;

use App\Entity\User;
use App\Service\Mailer\Mailer;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * USER SERVICES CLASS
 * Class UserServices
 * @package App\Service\User
 */
class UserServices
{
    private $doctrine;
    private $mailer;
    private $passwordEncoder;

    const NO_USER_FOUND  = "Aucun utilisateur trouvé";
    const ERROR_MAILER   = "Une erreur est survenue lors de l'envoie du mail";
    const STANDARD_ERROR = "Une erreur s'est produite.";

    public function __construct(
        RegistryInterface $doctrine,
        Mailer $mailer,
        UserPasswordEncoderInterface $encoder
    ) {

        $this->doctrine        = $doctrine;
        $this->mailer          = $mailer;
        $this->passwordEncoder = $encoder;
    }

    /**
     * CONFIRM THE USER ACCUNT
     * @param User $user
     * @throws \Exception
     */
    public function accuntConfirmation(User $user):void
    {
        // WE VALIDATE & RENGENERATE TOKEN
        $user->setActive(1);
        $user->setToken($user->generateToken());

        //EM
        $this->doctrine->getManager()->flush();
    }

    /**
     * FORGOT PASSWORD SEND MAIL
     * @param $userName
     * @return array
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function forgotPassword($userName):array
    {

        $user = $this->doctrine->getRepository(User::class)->findOneBy(['username' => $userName]);
        // VERIF IF USER EXIST
        if (!$user) {
            return array('statut' => false, 'error' => self::NO_USER_FOUND);
        }

        if (!$this->mailer->sendResetPass($user)) {
            return array('statut' => false, 'error' => self::ERROR_MAILER);
        }

        return array('statut' => true);
    }

    /**
     * REGISTER NEW USER
     * @param User $user
     * @return bool
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function registerUser(User $user):bool
    {
        // ENCODE PASSWORD
        $password = $this->passwordEncoder->encodePassword($user, $user->getPlainPassword());
        $user->setPassword($password);

        // SAVE THE USER
        $entityManager = $this->doctrine->getManager();
        $entityManager->persist($user);
        $entityManager->flush();

        // MAILER
        if ($this->mailer->sendRegisterConfirmation($user)) {
            return true;
        }

        return false;
    }

    /**
     * RESET THE PASSWORD USER
     * @param User $formUser
     * @param $token
     * @return array
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Exception
     */
    public function resetPassword(User $formUser, $token):array
    {
        $user = $this->doctrine->getRepository(User::class)->findOneBy(['email' => $formUser->getEmail()]);

        // CHECK IF USER EXIST
        if (!$user) {
            return array('statut' => false, 'error' => self::NO_USER_FOUND);
        }

        // CHECK IF TOKENS MATCH
        if ($token !== $user->getToken()) {
            return array('statut' => false, 'error' => self::STANDARD_ERROR);
        }

        // PASSWORD ENCODER
        $password = $this->passwordEncoder->encodePassword($formUser, $formUser->getPlainPassword());
        $user->setPassword($password);

        // REGENERATE TOKEN
        $user->setToken($user->generateToken());

        $this->doctrine->getEntityManager()->flush();

        return array('statut' => true);
    }
}
