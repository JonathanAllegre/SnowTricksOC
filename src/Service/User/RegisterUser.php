<?php
/**
 * Created by PhpStorm.
 * User: jonathan
 * Date: 12/06/2018
 * Time: 19:54
 */

namespace App\Service\User;

use App\Entity\User;
use App\Service\Mailer\Mailer;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * SAVE AN USER
 * Class RegisterUser
 * @package App\Service\User
 */
class RegisterUser
{
    private $doctrine;
    private $passwordEncoder;
    private $mailer;

    /**
     * RegisterUser constructor.
     * @param RegistryInterface $doctrine
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param Mailer $mailer
     */
    public function __construct(
        RegistryInterface $doctrine,
        UserPasswordEncoderInterface $passwordEncoder,
        Mailer $mailer
    ) {

        $this->doctrine = $doctrine;
        $this->passwordEncoder = $passwordEncoder;
        $this->mailer = $mailer;
    }

    /**
     * @param User $user
     * @return bool
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function register(User $user):bool
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
}
