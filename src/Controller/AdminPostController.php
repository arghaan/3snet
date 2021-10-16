<?php

namespace App\Controller;

use App\Entity\Post;
use App\Enum\PostActionsType;
use App\Form\PostType;
use App\Message\PostMessage;
use App\Service\PostService;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

class AdminPostController extends AbstractController
{
    private MessageBusInterface $bus;

    public function __construct(MessageBusInterface $bus)
    {
        $this->bus = $bus;
    }


    /**
     * @Route("/admin", methods={"GET"})
     */
    public function list(Request $request, PostService $postService): Response
    {
        $searchText = $request->request->get('search');
        $page = (int)$request->query->get('page', 1);
        $posts = $postService->getPosts($page, $searchText);
        return $this->render('admin/index.html.twig', [
            'posts' => $posts,
            'search' => $searchText,
        ]);
    }

    /**
     * @Route("/admin/post")
     */
    public function create(Request $request): Response
    {
        $post = new Post();
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Post $post */
            $post = $form->getData();
            $post->setCreatedAt(new DateTime());
            $message = new PostMessage(
                $post,
                PostActionsType::CREATE
            );
            $this->bus->dispatch($message);
            return $this->redirectToRoute('app_adminpost_list');
        }
        return $this->renderForm('admin/post.html.twig', [
            'form' => $form,
        ]);
    }

    /**
     * @Route("/admin/post/{id}/edit")
     */
    public function edit(Post $post, Request $request): Response
    {
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Post $post */
            $post = $form->getData();
            $message = new PostMessage(
                $post,
                PostActionsType::EDIT
            );
            $this->bus->dispatch($message);
            return $this->redirectToRoute('app_adminpost_list');
        }
        return $this->renderForm('admin/post.html.twig', [
            'form' => $form,
        ]);
    }

    /**
     * @Route("/admin/post/{id}/delete")
     */
    public function delete(Post $post): RedirectResponse
    {
        $message = new PostMessage(
            $post,
            PostActionsType::DELETE
        );
        $this->bus->dispatch($message);
        return $this->redirectToRoute('app_adminpost_list');
    }
}
