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
        return [
            'page' => $page,
            'comments' => $this->getDoctrine()->getRepository(Comment::class)->findBy(
                ['trick' => $trick],
                ['id' => 'DESC'],
                $perPage,
                $page
            )
        ];
    }
}
