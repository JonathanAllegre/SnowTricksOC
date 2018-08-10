<?php
/**
 * Created by PhpStorm.
 * User: jonathan
 * Date: 03/08/2018
 * Time: 08:07
 */

namespace App\Service;

use App\Entity\Trick;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Config\Definition\Exception\Exception;
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

    /**
     * @param Form $formTrick
     * @return mixed
     */
    public function add(Form $formTrick):?Trick
    {
        $trick = (new Trick())
            ->setName($formTrick->get('name')->getData())
            ->setDescription($formTrick->get('name')->getData())
            ->setUser($this->security->getUser())
            ->setFamily($formTrick->get('family')->getData());

        $this->doctrine->getManager()->persist($trick);
        try {
            $this->doctrine->getManager()->flush();
        } catch (UniqueConstraintViolationException $e) {
            return null;
        }

        return $trick;
    }
}
