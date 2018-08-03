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
use App\Service\UploadPictureService;
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
    public function add(Request $request, UploadPictureService $uploadPictureService)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');


        $formTrick = $this->createForm(AddTrickType::class);

        // HANDLE REQUEST & SAVE TRICK *
        $formTrick->handleRequest($request);
        if ($formTrick->isSubmitted() && $formTrick->isValid()) {
            $trick = (new Trick())
                ->setName($formTrick->get('name')->getData())
                ->setDescription($formTrick->get('name')->getData())
                ->setUser($this->getUser())
                ->setFamily($formTrick->get('family')->getData());

            $this->getDoctrine()->getManager()->persist($trick);
            $this->getDoctrine()->getManager()->flush();

            // if upload picture on upload
            if ($formTrick->get('image')->getData()) {
                $imgUploaded = $uploadPictureService->upload($formTrick->get('image')->getData());

                $picture = (new Picture())
                    ->setName($imgUploaded)
                    ->setTrick($trick)
                    ->setCreated(new \DateTime());

                $this->getDoctrine()->getManager()->persist($picture);
                $this->getDoctrine()->getManager()->flush();

            }

            if ($formTrick->get('video')->getData()) {
                $video = (new Video())
                    ->setCreated(new \DateTime())
                    ->setUrl($formTrick->get('video')->getData())
                    ->setTrick($trick);

                $this->getDoctrine()->getManager()->persist($video);
                $this->getDoctrine()->getManager()->flush();


            }

            return [];
        }

        //todo: refactor addtrick
        //todo: Flash message si tout c bien passé,
        //todo: Ajouter plusieurs images
        //todo: Ajouter plusieurs videos


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
