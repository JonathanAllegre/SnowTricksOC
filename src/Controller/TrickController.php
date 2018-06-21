<?php

namespace App\Controller;

use App\Entity\Picture;
use App\Entity\Trick;
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
        //
        // FIND PICTURE FOR TRICK
        $trick = $this->getDoctrine()->getRepository(Trick::class)->find(8);

        $pictures = $this->getDoctrine()
            ->getRepository(Picture::class)
            ->findBy(['trick' => $trick]);


        foreach ($pictures as $picture) {
            var_dump($picture->getName());
        }
        var_dump($pictures);
        /*
        // CREATE PICTURE FOR 1 TRICK
        $trick = $this->getDoctrine()->getRepository(Trick::class)->find(8);

        $picture = new Picture();
        $picture->setName('url/de/laphoto2');
        $picture->setCreated(new \DateTime());
        $picture->setTrick($trick);

        $this->getDoctrine()->getManager()->persist($picture);
        $this->getDoctrine()->getManager()->flush();



        /*
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
