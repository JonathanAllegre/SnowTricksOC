<?php
/**
 * Created by PhpStorm.
 * User: jonathan
 * Date: 09/06/2018
 * Time: 13:10
 */

namespace App\Service\Mailer;

use App\Entity\User;

class Mailer
{
    private $mailer;
    private $from;
    private $twig;

    public function __construct(\Swift_Mailer $mailer, \Twig_Environment $twig)
    {
        $this->mailer = $mailer;
        $this->from   = 'jonathan.allegre258@orange.fr';
        $this->twig   = $twig;
    }

    /**
     * SEND A CONFIRMATION MAIL TO USER WITH AN ACTIVATE ACCOUNT TOKEN
     * @return bool
     * @param User $user
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function sendRegisterConfirmation(User $user):bool
    {
        $view = $this->twig->render('mailer/register.html.twig', [
            'name' => $user->getUsername(),
            'token' => $user->getToken(),
        ]);

        $subject = "SnowTricks: Confirmation de compte";
        $to      = $user->getEmail();

        return $this->send($view, $subject, $to);
    }

    /**
     * SEND A RESET PASS TOKEN TO USER
     * @param User $user
     * @return bool
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function sendResetPass(User $user):bool
    {
        $view = $this->twig->render('mailer/forgot.html.twig', [
            'name'  => $user->getUsername(),
            'token' => $user->getToken(),
        ]);

        $subject = "SnowTricks: Réinitialisation de mot de passe";
        $to      = $user->getEmail();

        return $this->send($view, $subject, $to);
    }

    private function send($view, $subject, $to)
    {

        $message = (new \Swift_Message($subject))
            ->setFrom($this->from)
            ->setTo($to)
            ->setBody($view, 'text/html');

        if ($this->mailer->send($message)) {
            return true;
        }

        return false;
    }
}