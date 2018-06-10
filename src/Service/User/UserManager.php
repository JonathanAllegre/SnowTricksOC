<?php
/**
 * Created by PhpStorm.
 * User: jonathan
 * Date: 10/06/2018
 * Time: 18:23
 */

namespace App\Service\User;

use App\Entity\User;
use App\Service\Mailer\Mailer;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserManager
{
    private $passwordEncoder;
    private $doctrine;
    private $mailer;

    public function __construct(
        UserPasswordEncoderInterface $passwordEncoder,
        RegistryInterface $doctrine,
        Mailer $mailer
    ) {
        $this->passwordEncoder = $passwordEncoder;
        $this->doctrine = $doctrine;
        $this->mailer = $mailer;
    }

    /**
     * @return bool
     * @param User $user
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
     * @param User $user
     * @throws \Exception
     */
    public function accountConfirmation(User $user)
    {
        // WE VALIDATE & RENGENERATE TOKEN
        $user->setActive(1);
        $user->setToken($user->generateToken());

        //EM
        $this->doctrine->getManager()->flush();
    }

}
