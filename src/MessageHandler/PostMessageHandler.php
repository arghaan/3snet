<?php

namespace App\MessageHandler;

use App\Enum\PostActionsType;
use App\Message\PostMessage;
use App\Repository\PostRepository;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class PostMessageHandler implements MessageHandlerInterface
{
    private PostRepository $postRepository;
    private KernelInterface $kernel;

    public function __construct(PostRepository $postRepository, KernelInterface $kernel)
    {
        $this->postRepository = $postRepository;
        $this->kernel = $kernel;
    }

    public function __invoke(PostMessage $message)
    {
        switch ($message->getAction()) {
            case PostActionsType::CREATE:
            case PostActionsType::EDIT:
                $this->postRepository->editPost($message->getPost());
                break;
            case PostActionsType::DELETE:
                $this->postRepository->deletePost($message->getPost());
                break;
        }
    }

    private function clearCache()
    {

    }
}
