<?php
/**
 * Created by PhpStorm.
 * User: jonathan
 * Date: 12/06/2018
 * Time: 20:41
 */

namespace App\Service\User;


class UserFactory
{
    private $accuntConfirmation;
    private $registerUser;
    private $forgotPass;

    /**
     * UserFactory constructor.
     * @param AccountConfirmation $accuntConfirmation
     * @param RegisterUser $registerUser
     * @param ForgotPass $forgotPass
     */
    public function __construct(
        AccountConfirmation $accuntConfirmation,
        RegisterUser $registerUser,
        ForgotPass $forgotPass
    ) {
        $this->accuntConfirmation = $accuntConfirmation;
        $this->registerUser       = $registerUser;
        $this->forgotPass         = $forgotPass;
    }


    public function createNewAccuntConfirmation()
    {
        return $this->accuntConfirmation;
    }

    public function createNewRegisterUser()
    {
        return $this->registerUser;
    }

    public function createNewForgotPass()
    {
        return $this->forgotPass;
    }
}