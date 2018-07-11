<?php

namespace App\Controller;

use App\Entity\Trick;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class CommentController extends Controller
{
    /**
     * @Route("comment/loadMore/{trick}/{page}")
     * @Template()
     */
    public function loadMoreComment(Trick $trick, $page = 1)
    {

        return [];
    }


}
