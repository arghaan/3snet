<?php

namespace App\Command;

use App\Service\CacheService;
use Psr\Cache\InvalidArgumentException;
use RuntimeException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class CacheRedisClearCommand extends Command
{
    protected static $defaultName = 'cache:redis:clear';
    protected static $defaultDescription = 'Clear cache of Redis';
    private CacheService $cache;
    const KEYS = [
        'rating',
        'first'
    ];

    /**
     * @param CacheService $cache
     */
    public function __construct(CacheService $cache)
    {
        parent::__construct();
        $this->cache = $cache;
    }


    protected function configure(): void
    {
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        foreach (self::KEYS as $key) {
            try {
                $this->cache->delete($key);
            }catch (InvalidArgumentException $e) {
                throw new RuntimeException($e->getMessage());
            }
        }
        $io->success('Cache cleared.');

        return Command::SUCCESS;
    }
}
