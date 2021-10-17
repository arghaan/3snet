<?php

namespace App\MessageHandler;

use App\Enum\PostActionsType;
use App\Message\PostMessage;
use App\Repository\PostRepository;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class PostMessageHandler implements MessageHandlerInterface
{
    private PostRepository $postRepository;

    public function __construct(PostRepository $postRepository)
    {
        $this->postRepository = $postRepository;
    }

    public function __invoke(PostMessage $message)
    {
        switch ($message->getAction()) {
            case PostActionsType::CREATE:
                $this->postRepository->savePost($message->getPost());
                break;
            case PostActionsType::EDIT:
                $this->postRepository->editPost($message->getPost());
                break;
            case PostActionsType::DELETE:
                $this->postRepository->deletePost($message->getPost());
                break;
        }

    }
}
