<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Picture;
use App\Entity\Trick;
use App\Entity\Video;
use App\Form\TrickAddType;
use App\Form\CommentAddType;
use App\Service\CommentService;
use App\Service\TrickService;
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
        $formComment = $this->createForm(CommentAddType::class);

        // HANDLE REQUEST & SAVE COMMENT *
        $formComment->handleRequest($request);
        if ($formComment->isSubmitted() && $formComment->isValid()) {
            $comment = (new Comment())
                ->setUser($this->getUser())
                ->setTrick($trick)
                ->setContent($formComment->get('content')->getData())
            ;
            $commentService->add($comment);
            $this->addFlash('success', 'Votre commentaire a bien été enregistré');

            return $this->redirectToRoute('app_trick_detail', ['id' => $trick->getId(), 'slug' => $trick->getSlug()]);
        }

        $doctrine = $this->getDoctrine();
        $comments = $doctrine->getRepository(Comment::class)
            ->findBy(['trick' => $trick], ['id' => 'DESC'], Comment::PER_PAGE, 0);

        return [
            'trick'         => $trick,
            'pics'          => $doctrine->getRepository(Picture::class)->findBy(['trick'=> $trick]),
            'vids'          => $doctrine->getRepository(Video::class)->findBy(['trick'=> $trick]),
            'comments'      => $comments,
            'totalComments' => count($doctrine->getRepository(Comment::class)->findBy(['trick'=> $trick])),
            'perPage'       => Comment::PER_PAGE,
            'form'          => $formComment->createView()
        ];
    }

    /**
     * @Template()
     * @Route("/trick/update/{id}")
     */
    public function update(Trick $trick, Request $request, TrickService $trickSer)
    {
        $formTrick = $this->createForm(TrickAddType::class, $trick);
        $doctrine = $this->getDoctrine();

        $pictures = $doctrine->getRepository(Picture::class)->findBy(['trick' => $trick]);
        $videos   = $doctrine->getRepository(Video::class)->findBy(['trick' => $trick]);

        $formTrick->handleRequest($request);
        if ($formTrick->isSubmitted() && $formTrick->isValid()) {
            // CHECK IF 1 OR MANY PICTURES ARE UPLAODED: IF TRUE UPLOAD & PERSIST FILE
            $trickSer->trickHasPicture($trick);
            $doctrine->getManager()->flush();

            $this->addFlash('success', 'Le Trick a bien été sauvegardé.');

            return $this->redirectToRoute('app_trick_update', ['id' => $trick->getId()]);
        }


        return ['trick' => $trick, 'pics' => $pictures, 'vids' => $videos, 'form' => $formTrick->createView()];
    }

    /**
     * @Route("/trick/add")
     * @Template
     */
    public function add(Request $request, TrickService $trickSer)
    {
        $trick = new Trick();
        $formTrick = $this->createForm(TrickAddType::class, $trick);

        // HANDLE REQUEST & SAVE TRICK *
        $formTrick->handleRequest($request);
        if ($formTrick->isSubmitted() && $formTrick->isValid()) {
            $trick->setUser($this->getUser());

            // CHECK IF 1 OR MANY PICTURES ARE UPLAODED: IF TRUE UPLOAD & PERSIST FILE
            $trickSer->trickHasPicture($trick);

            // PERSIST & FLUSH
            $this->getDoctrine()->getManager()->persist($trick);
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'Le trick à bien été enregistré.');

            return $this->redirectToRoute('app_home_index');
        }

        return ['form' => $formTrick->createView()];
    }
}
