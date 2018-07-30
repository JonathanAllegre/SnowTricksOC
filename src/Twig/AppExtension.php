<?php
/**
 * Created by PhpStorm.
 * User: jonathan
 * Date: 30/07/2018
 * Time: 16:07
 */

namespace App\Twig;

use App\Entity\Picture;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{
    private $doctrine;

    public function __construct(RegistryInterface $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    public function getFunctions()
    {
        return array(
            new TwigFunction('firstPicture', array($this, 'getFirstPicture')),
        );
    }

    public function firstPicture()
    {
        return "lkjlkjlkj";
    }

    public function getFirstPicture(int $trick)
    {
        $firstPicture = $this
            ->doctrine
            ->getRepository(Picture::class)
            ->findOneBy(['trick' => $trick], ['id' => 'ASC']);

        if (!$firstPicture) {
            return "http://blog.kesi-art.com/wp-content/uploads/elementor/thumbs/VISUEL-NON-DISPONIBLE-nchgtciah9pdkun8mutbeagowv4fc9zk23qz6hmx8m.png";
        }
        return $firstPicture->getName();
    }
}
