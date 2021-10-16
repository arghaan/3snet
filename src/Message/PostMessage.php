<?php

namespace App\Message;

use App\Entity\Post;

final class PostMessage
{
    private Post $post;
    private int $action;

    public function __construct(Post $post, int $action)
    {
        $this->post = $post;
        $this->action = $action;
    }

    public function getPost(): Post
    {
        return $this->post;
    }

    public function setPost(Post $post): self
    {
        $this->post = $post;
        return $this;
    }

    public function getAction(): int
    {
        return $this->action;
    }

    public function setAction(int $action): self
    {
        $this->action = $action;
        return $this;
    }


}
