<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Trick;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class CommentController extends Controller
{
    /**
     * @Route("comment/loadMore/{trick}/{page}/{perPage}", condition="request.isXmlHttpRequest()")
     * @Template()
     */
    public function loadMoreComment(Trick $trick, $page, $perPage)
    {
        $doctrine = $this->getDoctrine();
        $comments = $doctrine->getRepository(Comment::class)
            ->findBy(['trick' => $trick], ['id' => 'DESC'], $perPage, $page);

        return ['page' => $page, 'comments' => $comments];
    }
}
