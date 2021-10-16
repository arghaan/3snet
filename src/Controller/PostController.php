<?php

namespace App\Controller;

use App\Entity\Post;
use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PostController extends AbstractController
{
    /**
     * @Route("/{id}", requirements={"id"="\d+"})
     */
    public function show(Post $post, Request $request, PostRepository $repository): Response
    {
        return $this->render('post.html.twig', [
            'post' => $repository->getPost($post, $request->server->get('REMOTE_ADDR'))[0]
        ]);
    }
}
