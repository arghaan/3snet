<?php

namespace App\Service;

use App\Entity\Post;
use App\Entity\Rating;
use App\Repository\PostRepository;
use App\Repository\RatingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Cache\InvalidArgumentException;

class RatingService
{
    private EntityManagerInterface $em;
    private RatingRepository $repository;
    private PostRepository $postRepository;
    private CacheService $cache;

    public function __construct(
        EntityManagerInterface $em,
        RatingRepository       $repository,
        PostRepository         $postRepository,
        CacheService $cache
    )
    {
        $this->em = $em;
        $this->repository = $repository;
        $this->postRepository = $postRepository;
        $this->cache = $cache;
    }

    public function addRating(Rating $rating)
    {
        try {
            $this->cache->delete('rating');
        } catch (InvalidArgumentException $e) {

        } finally {
            $this->em->persist($rating);
            $this->em->flush();
        }
    }

    public function hasRating(Post $post, string $ip): bool
    {
        return !!$this->repository->findOneBy(['post' => $post, 'ip' => $ip]);
    }

    public function getRating(Post $post): int
    {
        return $this->repository->getRating($post);
    }

    public function getTop10()
    {
        $rating = $this->cache->get('rating');
        if ($rating->isHit()) {
            return $rating->get();
        }
        $items = $this->postRepository->getTop10();
        $tags = [];
        /** @var Post $item */
        foreach ($items as $item) {
            $tags[] = 'post_'.$item['id'];
        }
        $this->cache->save('rating', $items, $tags, (int)$_ENV['REDIS_EXPIRE_AFTER']);
        return $items;
    }
}