<?php
/**
 *
 * Created by PhpStorm.
 * User: jonathan
 * Date: 13/06/2018
 * Time: 15:09
 */

namespace App\Service\User;

use App\Entity\User;
use App\Service\Mailer\Mailer;
use Symfony\Bridge\Doctrine\RegistryInterface;

class ForgotPass
{

    private $doctrine;
    private $mailer;

    const NO_USER_FOUND = "Aucun utilisateur trouvé";
    const ERROR_MAILER  = "Une erreur est survenue lors de l'envoie du mail";

    public function __construct(RegistryInterface $doctrine, Mailer $mailer)
    {
        $this->doctrine = $doctrine;
        $this->mailer   = $mailer;
    }

    /**
     * CHECK IF USER EXIST AND THEN SEND A RESET TOKEN BY MAIL
     * @param $userName
     * @return array
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function forgot($userName):array
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

}