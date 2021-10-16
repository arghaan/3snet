<?php

namespace App\Service;

use App\Repository\PostRepository;
use Knp\Component\Pager\Pagination\PaginationInterface;

class PostService
{
    private PostRepository $postRepository;

    public function __construct(PostRepository $postRepository)
    {
        $this->postRepository = $postRepository;
    }

    public function getPosts(int $page = 1, string $searchText = null, string $ip = null): ?PaginationInterface
    {
        // TODO: Cache
        $posts = $this->postRepository->getPagination($page, $_ENV['POST_PER_PAGE'], $searchText, $ip);
        return $posts;
    }
}