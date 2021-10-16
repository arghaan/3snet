<?php

namespace App\Controller\API;

use App\Entity\Complaint;
use App\Entity\Post;
use App\Entity\Rating;
use App\Service\ComplaintService;
use App\Service\RatingService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class PostController extends AbstractController
{
    private ComplaintService $complaintService;
    private RatingService $ratingService;

    public function __construct(ComplaintService $complaintService, RatingService $ratingService)
    {
        $this->complaintService = $complaintService;
        $this->ratingService = $ratingService;
    }

    /**
     * @Route("/api/v1/post/{id}/complaint")
     */
    public function complaint(Post $post, Request $request): JsonResponse
    {
        if ($this->complaintService->hasComplaint($post, $request->server->get('REMOTE_ADDR'))) {
            return new JsonResponse(null, 400);
        }
        $complaint = new Complaint();
        $complaint->setPost($post)
            ->setIp($request->server->get('REMOTE_ADDR'));
        try {
            $this->complaintService->addComplaint($complaint);
            return new JsonResponse();
        } catch (Exception $e) {
            return new JsonResponse(['message' => $e->getMessage(), 500]);
        }
    }

    /**
     * @Route("/api/v1/post/{id}/rating")
     */
    public function rating(Post $post, Request $request): JsonResponse
    {
        if ($this->ratingService->hasRating($post, $request->server->get('REMOTE_ADDR'))) {
            return new JsonResponse(null, 400);
        }
        $rating = new Rating();
        $rating->setPost($post)
            ->setRating((int)$request->request->get('rating'))
            ->setIp($request->server->get('REMOTE_ADDR'));
        try {
            $this->ratingService->addRating($rating);
            return new JsonResponse();
        } catch (Exception $e) {
            return new JsonResponse(['message' => $e->getMessage(), 500]);
        }
    }

}
