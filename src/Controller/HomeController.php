<?php

namespace App\Controller;

use App\Entity\Trick;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class HomeController extends Controller
{
    /**
     * @Route("/", name="home")
     * @Template();
     */
    public function index()
    {
        $tricks = $this->getDoctrine()
            ->getRepository(Trick::class)
            ->getTricksWithListingPicture();

        return  ['tricks' => $tricks];
    }

}
