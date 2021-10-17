<?php

namespace App\Service;

use App\Entity\Post;
use App\Repository\PostRepository;
use Knp\Component\Pager\Pagination\PaginationInterface;

class PostService
{
    private PostRepository $postRepository;
    private CacheService $cache;

    public function __construct(
        PostRepository $postRepository,
        CacheService $cache
    )
    {
        $this->postRepository = $postRepository;
        $this->cache = $cache;
    }

    public function getPosts(int $page = 1, string $searchText = null, string $ip = null): ?PaginationInterface
    {
        if (null === $page || 1 === $page) {
            $first = $this->cache->get('first');
            if ($first->isHit()) {
                return $first->get();
            }
            $posts = $this->postRepository->getPagination($page, $_ENV['POST_PER_PAGE'], $searchText, $ip);
            $tags = [];
            /** @var Post $item */
            foreach ($posts as $item) {
                $tags[] = 'post_'.$item['id'];
            }
            $this->cache->save('first', $posts, $tags, (int)$_ENV['REDIS_EXPIRE_AFTER']);
        } else {
            $posts = $this->postRepository->getPagination($page, $_ENV['POST_PER_PAGE'], $searchText, $ip);
        }
        return $posts;
    }
}