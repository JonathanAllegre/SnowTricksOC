<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Family;
use App\Entity\Picture;
use App\Entity\Trick;
use App\Entity\Video;
use App\Form\CommentAddType;
use App\Service\CommentService;
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
            $manager = $this->getDoctrine()->getManager();
            // REMOVE TRICK
            $manager->remove($trick);
            $manager->flush();
            $this->addFlash('success', 'Le trick "'. $trick->getName().'" à bien été supprimé');

            return $this->redirectToRoute('app_home_index');
        }

        $this->addFlash('warning', "Une erreur s'est produite");

        return $this->redirectToRoute('app_home_index');
    }

    /**
     * @Template()
     * @Route("/trick/detail/{id}/{slug}")
     */
    public function detail(Request $request, Trick $trick, CommentService $commentService)
    {
        // CREATE COMMENT FORM
        $comment = new Comment();
        $comment->setTrick($trick);
        $this->isGranted('IS_AUTHENTICATED_FULLY') ? $comment->setUser($this->getUser()): $comment->setUser('');

        $formComment = $this->createForm(CommentAddType::class, $comment);


        // HANDLE REQUEST & SAVE COMMENT
        $formComment->handleRequest($request);
        if ($formComment->isSubmitted() && $formComment->isValid()) {
            $commentService->addComment($comment);
            $this->addFlash('success', 'Votre commentaire a bien été enregistré');

            return $this->redirectToRoute('app_trick_detail', ['id' => $trick->getId(), 'slug' => $trick->getSlug()]);
        }

        $doctrine = $this->getDoctrine();

        return [
            'trick'         => $trick,
            'pics'          => $doctrine->getRepository(Picture::class)->findBy(['trick'=> $trick]),
            'vids'          => $doctrine->getRepository(Video::class)->findBy(['trick'=> $trick]),
            'comments'      => $doctrine->getRepository(Comment::class)->getCommentPaginate($trick),
            'totalComments' => count($doctrine->getRepository(Comment::class)->findBy(['trick'=> $trick])),
            'form'          => $formComment->createView()
        ];
    }

    /**
     * @param Trick $trick
     * @return array
     * @Template()
     * @Route("/trick/update/{id}")
     */
    public function update(Trick $trick)
    {
        $trick->setDescription('lkjlkjlkj');
        $this->getDoctrine()->getManager()->flush();
        return [];
    }

    /**
     * @Route("/trick/add")
     * @Template
     */
    public function add()
    {
        $family = $this->getDoctrine()->getRepository(Family::class)->findOneBy(['title' => 'grab']);

        $trick = new Trick();
        $trick->setName("testt coll :) éé $ **%");
        $trick->setDescription('ma description');
        $trick->setCreated(new \DateTime());
        $trick->setUser($this->getUser());
        $trick->setFamily($family);

        $this->getDoctrine()->getManager()->persist($trick);
        $this->getDoctrine()->getManager()->flush();
    }
}
