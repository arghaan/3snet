<?php

namespace App\Controller;

use App\Repository\PostRepository;
use App\Service\PostService;
use App\Service\RatingService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RatingController extends AbstractController
{
    /**
     * @Route("/rating", name="rating")
     */
    public function index(RatingService $ratingService): Response
    {
        return $this->render('rating/index.html.twig', [
            'posts' => $ratingService->getTop10()
        ]);
    }
}
