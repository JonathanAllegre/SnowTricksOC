<?php
/**
 * Created by PhpStorm.
 * User: jonathan
 * Date: 03/08/2018
 * Time: 08:07
 */

namespace App\Service;

use App\Entity\Trick;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Form\Form;
use Symfony\Component\Security\Core\Security;

class TrickService
{

    private $doctrine;
    private $security;

    public function __construct(RegistryInterface $doctrine, Security $security)
    {
        $this->doctrine = $doctrine;
        $this->security = $security;

    }

    public function add(Form $formTrick)
    {
        $trick = (new Trick())
            ->setName($formTrick->get('name')->getData())
            ->setDescription($formTrick->get('name')->getData())
            ->setUser($this->security->getUser())
            ->setFamily($formTrick->get('family')->getData());

        $this->doctrine->getManager()->persist($trick);
        $this->doctrine->getManager()->flush();

        return $trick;
    }
}
