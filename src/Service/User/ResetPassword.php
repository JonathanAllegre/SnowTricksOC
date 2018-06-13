<?php
/**
 * Created by PhpStorm.
 * User: jonathan
 * Date: 13/06/2018
 * Time: 17:48
 */

namespace App\Service\User;

use App\Entity\User;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ResetPassword
{

    private $passwordEncoder;
    private $doctrine;

    const NO_USER_FOUND  = "Aucun utilisateur trouvé";
    const STANDARD_ERROR = "Une erreur s'est produite.";

    public function __construct(UserPasswordEncoderInterface $passwordEncoder, RegistryInterface $doctrine)
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->doctrine        = $doctrine;
    }

    /**
     * @param User $formUser
     * @param $token
     * @return array
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Exception
     */
    public function reset(User $formUser, $token):array
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
