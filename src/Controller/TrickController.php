<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Picture;
use App\Entity\Trick;
use App\Entity\Video;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
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
     * @Route("/trick/delete/{id}")
     */
    public function delete(Trick $trick, Request $request)
    {
        // CHECK IF USER IS CONNECT
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // CHECK CSRF
        if ($this->isCsrfTokenValid('delete-trick', $request->request->get('token'))) {
            // REMOVE TRICK
            $this->getDoctrine()->getManager()->remove($trick);
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'Le trick "'. $trick->getName().'" à bien été supprimé');

            return $this->redirectToRoute('app_home_index');
        }

        $this->addFlash('warning', "Une erreur s'est produite");

        return $this->redirectToRoute('app_home_index');
    }

    /**
     * @param Trick $trick
     * @return array
     * @Template()
     * @Route("/trick/detail/{id}/{slug}")
     */
    public function detail(Trick $trick)
    {
        $pics     = $this->getDoctrine()->getRepository(Picture::class)->findBy(['trick'=> $trick]);
        $vids     = $this->getDoctrine()->getRepository(Video::class)->findBy(['trick'=> $trick]);
        $comments = $this->getDoctrine()->getRepository(Comment::class)->findBy(['trick' => $trick]);

        return ['trick' => $trick, 'pics' => $pics, 'vids' => $vids, 'comments' => $comments];
    }

    /**
     * @param Trick $trick
     * @return array
     * @Template()
     * @Route("/trick/update/{id}")
     */
    public function update(Trick $trick)
    {
        var_dump($trick);
        return [];
    }
}
