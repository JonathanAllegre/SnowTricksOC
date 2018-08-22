<?php

namespace App\Controller;

use App\Entity\Picture;
use App\Entity\Trick;
use App\Service\PictureService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class PictureController extends Controller
{
    /**
     * @Route("/picture", name="picture")
     * @Template()
     */
    public function index()
    {
        return [];
    }

    /**
     * @Route("/picture/setListingPicture", methods={"POST"})
     */
    public function setListingPicture(Request $request, PictureService $pictureService)
    {

        $submitedToken = $request->request->get('token');
        $doctrine      = $this->getDoctrine();

        $trick   = $doctrine->getRepository(Trick::class)->findOneBy(['id' => $request->request->get('trickId')]);
        $picture = $doctrine->getRepository(Picture::class)->findOneBy(['id' => $request->request->get('oneImg')]);

        if ($this->isCsrfTokenValid('addListingPicture', $submitedToken)) {
            // PERSIST & FLUSH
            $pictureService->setListingPicture($picture, $trick);

            $this->addFlash('success', "L'image à la une a bien été modifiée");

            return $this->redirectToRoute('app_trick_update', ['id' => $trick->getId()]);
        }

        $this->addFlash('warning', "Un problème est survenue");

        return $this->redirectToRoute('app_trick_update', ['id' => $trick->getId()]);
    }
}
