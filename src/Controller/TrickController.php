<?php

namespace App\Controller;

use App\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class TrickController extends Controller
{
    /**
     * @Route("/trick", name="trick")
     * @Template()
     */
    public function index()
    {
        return ['controller_name' => 'trickController'];
    }

    /**
     * @Route("/trick/test", name="trick_test")
     * @Template()
     */
    public function test()
    {
        $user = $this->getDoctrine()->getRepository(User::class)->find(1);
        $tricks = $user->getTricks();

        foreach ($tricks as $trick) {
            var_dump($trick->getName());
        }
        /*
        $trick = $this->getDoctrine()->getRepository(Trick::class)->find(2);
        $user = $trick->getUser()->getUsername();*/

        //var_dump($tricks);
        return [];
    }
}
