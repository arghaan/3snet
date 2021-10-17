<?php

namespace App\Controller;

use App\Service\PostService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    private PostService $postService;

    public function __construct(PostService $postService)
    {
        $this->postService = $postService;
    }


    /**
     * @Route("/", name="index")
     */
    public function index(Request $request): Response
    {
        $searchText = $request->request->get('search');
        $page = (int)$request->query->get('page', 1);
        $posts = $this->postService->getPosts(true, $page, $searchText, $request->server->get('REMOTE_ADDR'));
        return $this->render('index.html.twig', [
            'pagination' => $posts,
            'search' => $searchText
        ]);
    }
}
