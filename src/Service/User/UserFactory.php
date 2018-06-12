<?php
/**
 * Created by PhpStorm.
 * User: jonathan
 * Date: 12/06/2018
 * Time: 20:41
 */

namespace App\Service\User;


use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFactory
{
    private $accuntConfirmation;
    private $registerUser;

    /**
     * UserFactory constructor.
     * @param $accuntConfirmation
     * @param $registerUser
     */
    public function __construct(AccountConfirmation $accuntConfirmation, RegisterUser $registerUser)
    {
        $this->accuntConfirmation = $accuntConfirmation;
        $this->registerUser = $registerUser;
    }


    public function createNewAccuntConfirmation()
    {
        return $this->accuntConfirmation;
    }

    public function createNewRegisterUser()
    {
        return $this->registerUser;
    }
}