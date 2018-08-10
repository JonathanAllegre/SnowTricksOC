<?php
/**
 * Created by PhpStorm.
 * User: jonathan
 * Date: 30/07/2018
 * Time: 16:07
 */

namespace App\Twig;

use App\Entity\Picture;
use App\Entity\Trick;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{
    private $doctrine;
    private $request;

    public function __construct(RegistryInterface $doctrine, RequestStack $request)
    {
        $this->doctrine = $doctrine;
        $this->request  = $request;
    }

    public function getFunctions()
    {
        return array(
            new TwigFunction('firstPicture', array($this, 'getFirstPicture')),
            new TwigFunction('renderPicture', array($this, 'renderPicture')),
        );
    }


    public function getFirstPicture(Trick $trick)
    {
        // CHECK IF TRICK HAS LISTING PICTURE
        if ($trick->getListingPicture()->getName()) {
            return $this->renderPicture($trick->getListingPicture()->getName());
        }

        // CHECK IF TRICK HAS AT LEAST ONE PICTURE
        $firstPicture = $this
            ->doctrine
            ->getRepository(Picture::class)
            ->findOneBy(['trick' => $trick], ['id' => 'ASC']);

        // IF TRICK HAS NO LISTING PICTURE & NO PICTURE
        if (!$firstPicture) {
            $url  = "http://blog.kesi-art.com/wp-content/uploads/elementor/thumbs/VISUEL-NON-DISPONIBLE";
            $url .= "-nchgtciah9pdkun8mutbeagowv4fc9zk23qz6hmx8m.png";

            return $url;
        }

        // IF IF TRICK HAS A PICTURE IN DB WE RETURN THE FIRST PICTURE OF THE TRICK
        return $this->renderPicture($firstPicture->getName());
    }

    public function renderPicture($pictureName)
    {
        if (substr($pictureName, 0, '4') === 'http') {
            return $pictureName;
        }

        $url  = $this->request->getCurrentRequest()->server->get('HHTP_HOST');
        $url .= "/img/tricks/";
        $url .= $pictureName;

        return $url;
    }
}
