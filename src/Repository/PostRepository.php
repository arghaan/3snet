<?php

namespace App\Repository;

use App\Entity\Post;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use Psr\Log\LoggerInterface;

/**
 * @method Post|null find($id, $lockMode = null, $lockVersion = null)
 * @method Post|null findOneBy(array $criteria, array $orderBy = null)
 * @method Post[]    findAll()
 * @method Post[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostRepository extends ServiceEntityRepository
{
    private PaginatorInterface $paginator;
    private EntityManagerInterface $em;
    private LoggerInterface $logger;

    public function __construct(
        ManagerRegistry        $registry,
        PaginatorInterface     $paginator,
        EntityManagerInterface $em,
        LoggerInterface $logger
    )
    {
        parent::__construct($registry, Post::class);
        $this->paginator = $paginator;
        $this->em = $em;
        $this->logger = $logger;
    }

    public function getPost(Post $post, string $ip)
    {
        return $this->createQueryBuilder('p')
            ->select('p.id, p.text, p.created_at')
            ->addSelect(
                '
                (
               SELECT count(1)
               from App\Entity\Complaint c
               where c.post = p.id
                   and c.ip = :ip
               ) com
            '
            )
            ->addSelect(
                '
                (
               SELECT count(1)
               from App\Entity\Rating r
               where r.post = p.id
                   and r.ip = :ip
               ) rating
            '
            )
            ->addSelect('
                (SELECT SUM(r2.rating)
                from App\Entity\Rating r2
                where r2.post = p) sum_rating
            ')
            ->andWhere('p = :post')
            ->setParameter('ip', $ip)
            ->setParameter('post', $post)
            ->getQuery()
            ->getResult();
    }

    public function getPagination(int $page = 1, int $postPerPage = 1, string $searchText = null, string $ip = null): PaginationInterface
    {
        $query = $this->createQueryBuilder('p');
        $query->select('p.id, p.text, p.created_at, p.modified_at, p.alias')
            ->addSelect(
                '
            (
               SELECT count(1)
               from App\Entity\Complaint c
               where c.post = p.id
                   and c.ip = :ip
           ) com
            '
            )
            ->addSelect(
                '
            (
               SELECT count(1)
               from App\Entity\Rating r
               where r.post = p.id
                   and r.ip = :ip
           ) rating
            '
            )
            ->addSelect('
            (SELECT SUM(r2.rating)
            from App\Entity\Rating r2
            where r2.post = p) sum_rating
            ')
            ->setParameter('ip', $ip);
        if (null !== $searchText) {
            $query->andWhere('p.text LIKE :search')
                ->setParameter('search', "%$searchText%");
        }
        $query->orderBy('p.created_at', 'DESC');
        return $this->paginator->paginate(
            $query,
            $page,
            $postPerPage,
            [
                'pageOutOfRange' => 'fix'
            ]
        );
    }

    public function getTop10()
    {
        $query = $this->createQueryBuilder('p');
        return $query->select('p.id, p.text, p.created_at')
            ->addSelect('
            (SELECT SUM(r.rating)
            from App\Entity\Rating r
            where r.post = p) rating
            ')
            ->orderBy('rating', 'DESC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }

    public function savePost(Post $message): void
    {
        $this->em->persist($message);
        $this->em->flush();
    }

    public function editPost(Post $message): void
    {
        $post = $this->find($message->getId());
        if (null !== $post) {
            $post->setText($message->getText())
                ->setCreatedAt($message->getCreatedAt())
                ->setAlias($message->getAlias())
                ->setModifiedAt(new DateTime());
            $this->em->persist($post);
            $this->em->flush();
        }
    }

    public function deletePost(Post $message): void
    {
        $post = $this->find($message->getId());

        if (null !== $post) {
            $this->em->remove($post);
            $this->em->flush();
        }
    }

    // /**
    //  * @return Post[] Returns an array of Post objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Post
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
