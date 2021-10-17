<?php

namespace App\EventListener;

use App\Entity\Post;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Event\PostFlushEventArgs;
use Psr\Log\LoggerInterface;
use Symfony\Component\Process\Process;

class PostEventListener
{
    private bool $needCacheClear = false;
    private LoggerInterface $logger;

    /**
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function onFlush(OnFlushEventArgs $args)
    {
        $em = $args->getEntityManager();
        $uow = $em->getUnitOfWork();
        foreach ($uow->getScheduledEntityUpdates() as $entity) {
            if ($entity instanceof Post) {
                $changeSet = $uow->getEntityChangeSet($entity);
                if (
                    key_exists('alias', $changeSet)
                    && $changeSet['alias'] != $entity->getAlias()
                )
                {
                    $this->needCacheClear = true;
                }
            }
        }

        foreach ($uow->getScheduledEntityInsertions() as $entity) {
            if ($entity instanceof Post) {
                if (null !== $entity->getAlias()) {
                    $this->needCacheClear = true;
                }
            }
        }
    }

    public function postFlush(PostFlushEventArgs $args)
    {
        if ($this->needCacheClear){
            $this->logger->info('Clear cache.');
            $process = new Process(
                ['/var/www/symfony/bin/console', 'ca:cl']
            );
            $process->run();
        }
    }
}