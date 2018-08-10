<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Family;
use App\Entity\Picture;
use App\Entity\Trick;
use App\Entity\Video;
use App\Form\AddTrickType;
use App\Form\CommentAddType;
use App\Service\CommentService;
use App\Service\PictureService;
use App\Service\TrickService;
use App\Service\VideoService;
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
     * @param Trick $trick
     * @return array
     * @Template()
     * @Route("/trick/update/{id}")
     */
    public function update(Trick $trick)
    {
        $trick->setName('le trick de ouf éé aa');
        $this->getDoctrine()->getManager()->flush();
        return [];
    }

    /**
     * @Route("/trick/add")
     * @Template
     */
    public function add(Request $request, PictureService $pictureSer, TrickService $trickSer, VideoService $videoSer)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $formTrick = $this->createForm(AddTrickType::class);

        // HANDLE REQUEST & SAVE TRICK *
        $formTrick->handleRequest($request);
        if ($formTrick->isSubmitted() && $formTrick->isValid()) {
            // ADD TRICK
            $trick = $trickSer->add($formTrick);
            // IF FLUSH FAIL
            if (null == $trick) {
                $this->addFlash('warning', 'Le trick ne peut être enregistré. Il existe déjà.');
                return $this->redirectToRoute('app_home_index');
            }

            // CHECK IF 1 OR MANY PICTURES ARE UPLAODED: IF TRUE UPLOAD & PERSIST FILE
            $pictureSer->formHasPicture($formTrick, $trick);

            //CHECK IF 1 OR MANY VIDEOS ARE UPLAODED: PERSIST URL
            $videoSer->formHasVideo($formTrick, $trick);

            $this->addFlash('success', 'Le trick à bien été enregistré.');

            return $this->redirectToRoute('app_home_index');
        }

        return ['form' => $formTrick->createView()];
    }

    /**
     * @Route("/trick/test")
     * @Template
     */
    public function test()
    {
        $family = $this->getDoctrine()->getRepository(Family::class)->findOneBy(['title' => 'grab']);

        $trick = new Trick();
        $trick->setName("testt coll :) é et oui");
        $trick->setDescription('ma description');
        $trick->setCreated(new \DateTime());
        $trick->setUser($this->getUser());
        $trick->setFamily($family);

        $this->getDoctrine()->getManager()->persist($trick);
        $this->getDoctrine()->getManager()->flush();
    }
}
