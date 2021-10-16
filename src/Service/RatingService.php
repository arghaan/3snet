<?php

namespace App\Service;

use App\Entity\Post;
use App\Entity\Rating;
use App\Repository\PostRepository;
use App\Repository\RatingRepository;
use Doctrine\ORM\EntityManagerInterface;

class RatingService
{
    private EntityManagerInterface $em;
    private RatingRepository $repository;
    private PostRepository $postRepository;

    public function __construct(
        EntityManagerInterface $em,
        RatingRepository       $repository,
        PostRepository         $postRepository
    )
    {
        $this->em = $em;
        $this->repository = $repository;
        $this->postRepository = $postRepository;
    }

    public function addRating(Rating $rating)
    {
        $this->em->persist($rating);
        $this->em->flush();
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
        return $this->postRepository->getTop10();
    }
}