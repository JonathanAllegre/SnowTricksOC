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
        $this->from   = 'snowtricks@snowtricks.com';
        $this->twig   = $twig;
    }

    /**
     * @param User $user
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function sendRegisterConfirmation(User $user)
    {

        $view = $this->twig->render('mailer/register.html.twig', [
            'name' => $user->getUsername(),
            'token' => $user->getToken(),
        ]);

        $message = (new \Swift_Message('Snowtricks - Confirmation'))
            ->setFrom($this->from)
            ->setTo($user->getEmail())
            ->setBody($view, 'text/html');

        $this->mailer->send($message);
    }

}