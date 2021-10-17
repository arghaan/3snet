<?php

namespace App\EventListener;

use App\Entity\Post;
use Doctrine\ORM\Event\PostFlushEventArgs;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Psr\Log\LoggerInterface;
use Symfony\Component\Process\Process;

class PostEventListener
{
    private LoggerInterface $logger;

    /**
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }


    public function postUpdate(Post $post, LifecycleEventArgs $event): void
    {
//        /** @var UnitOfWork $uow */
//        $uow = $event->getObjectManager()->getUnitOfWork();
//        $changeSet = $uow->getEntityChangeSet($post);
//        if (isset($changeSet['alias'])) {
//            list(, $new) = $changeSet['alias'];
//            if ($new) {
//                $this->logger->info('Clear cache.');
//                $process = new Process(
//                    ['/var/www/symfony/bin/console', 'ca:cl']
//                );
//                $process->run();
//                $this->logger->warning($process->getOutput());
//            }
//        }
    }

    public function postFlush(PostFlushEventArgs $args)
    {
        $em = $args->getEntityManager();
        $uow = $em->getUnitOfWork();
        $needClear = true;

        foreach ($uow->getScheduledEntityUpdates() as $entity) {
            if ($entity instanceof Post) {
                $needClear = true;
            }
        }

        foreach ($uow->getScheduledEntityInsertions() as $entity) {
            if ($entity instanceof Post) {
                $needClear = true;
            }
        }
        if ($needClear) {
            $this->logger->info('Clear cache.');
            $process = new Process(
                ['/var/www/symfony/bin/console', 'ca:cl']
            );
            $process->run();
            $this->logger->warning($process->getOutput());
        }
    }
}