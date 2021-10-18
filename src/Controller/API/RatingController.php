<?php

namespace App\Controller\API;

use App\Entity\Post;
use App\Service\RatingService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class RatingController extends AbstractController
{
    /**
     * @Route("/api/v1/rating/{id}")
     */
    public function getRating(Post $post, RatingService $ratingService): JsonResponse
    {
        $rating = $ratingService->getRating($post);
        return new JsonResponse(['rating' => $rating]);
    }
}
