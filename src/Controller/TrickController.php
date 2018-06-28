<?php

namespace App\Controller;

use App\Entity\Trick;
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
     * @Route("/trick/delete/{id}", name="trick_delete")
     */
    public function test(Trick $trick)
    {
        // CHECK IF USER IS CONNECT
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // REMOVE TRICK
        $this->getDoctrine()->getManager()->remove($trick);
        $this->getDoctrine()->getManager()->flush();

        // AD FLASH
        $this->addFlash('success', 'Le trick "'. $trick->getName().'" à bien été supprimé');
        return $this->redirectToRoute('home', ['_fragment' => 'tricks']);
    }
}
