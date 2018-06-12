<?php
/**
 * Created by PhpStorm.
 * User: jonathan
 * Date: 12/06/2018
 * Time: 20:02
 */

namespace App\Service\User;


use App\Entity\User;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * CONFIRM THE ACCOUNT OF THE USER
 * Class AccountConfirmation
 * @package App\Service\User
 */
class AccountConfirmation
{
    private $doctrine;

    public function __construct(RegistryInterface $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    /**
     * @param User $user
     * @throws \Exception
     */
    public function confirm(User $user):void
    {
        // WE VALIDATE & RENGENERATE TOKEN
        $user->setActive(1);
        $user->setToken($user->generateToken());

        //EM
        $this->doctrine->getManager()->flush();
    }
}