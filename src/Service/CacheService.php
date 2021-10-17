<?php

namespace App\Service;

use Psr\Cache\CacheException;
use Psr\Cache\InvalidArgumentException;
use Symfony\Component\Cache\CacheItem;
use Symfony\Contracts\Cache\TagAwareCacheInterface;

class CacheService
{
    private TagAwareCacheInterface $cache;

    public function __construct(TagAwareCacheInterface $cache)
    {
        $this->cache = $cache;
    }

    public function get(string $key): CacheItem
    {
        return $this->cache->getItem($key);
    }

    /**
     * @throws CacheException
     * @throws InvalidArgumentException
     */
    public function save(string $key, $items, ?array $tags, ?int $expires): void
    {
        $cacheItem = $this->get($key);
        $cacheItem->set($items)
            ->tag($tags)
            ->expiresAfter($expires);
        $this->cache->save($cacheItem);
    }

    /**
     * @throws InvalidArgumentException
     */
    public function delete(?string $key, array $tags = null): bool
    {
        if (null !== $key) {
            return $this->cache->delete($key);
        } else {
            if (null !== $tags) {
                return $this->cache->invalidateTags($tags);
            } else {
                return false;
            }
        }
    }

}