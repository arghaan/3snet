<?php

namespace App\Service;

use App\Entity\Complaint;
use App\Entity\Post;
use App\Repository\ComplaintRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;

class ComplaintService
{
    private EntityManagerInterface $em;
    private ComplaintRepository $repository;

    public function __construct(EntityManagerInterface $em, ComplaintRepository $repository)
    {
        $this->em = $em;
        $this->repository = $repository;
    }

    /**
     * @throws Exception
     */
    public function addComplaint(Complaint $complaint)
    {
        $this->em->persist($complaint);
        $this->em->flush();
    }

    public function hasComplaint(Post $post, string $ip): bool
    {
        return !!$this->repository->findOneBy(['post' => $post, 'ip' => $ip]);
    }
}