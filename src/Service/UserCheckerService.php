<?php
/**
 * Created by PhpStorm.
 * User: jonathan
 * Date: 09/06/2018
 * Time: 11:11
 */

namespace App\Service;

use App\Entity\User;
use Symfony\Component\Security\Core\Exception\AccountStatusException;
use Symfony\Component\Security\Core\Exception\DisabledException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserCheckerService implements UserCheckerInterface
{

    /**
     * Checks the user account before authentication.
     *
     * @throws AccountStatusException
     */
    public function checkPreAuth(UserInterface $user)
    {
        /*
        if (!$user instanceof User) {
            return;
        }
         */
        // user is deleted, show a generic Account Not Found message.
        if (!$user->isEnabled()) {
            throw new DisabledException();
        }
    }

    /**
     * Checks the user account after authentication.
     *
     * @throws AccountStatusException
     */
    public function checkPostAuth(UserInterface $user)
    {
    }
}