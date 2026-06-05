<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Cache\TaggableStore;

class CacheService
{
    /**
     * Global cache key prefix to avoid collisions.
     */
    protected string $prefix = 'puma';

    /**
     * Determine if the current cache store natively supports tags.
     */
    public function supportsTags(): bool
    {
        return Cache::getStore() instanceof TaggableStore;
    }

    /**
     * Store an item in the cache if it doesn't exist, using a tag.
     */
    public function remember(string $tag, string $key, $ttl, \Closure $callback)
    {
        if ($this->supportsTags()) {
            $namespacedKey = "{$this->prefix}:{$key}";
            return Cache::tags([$tag])->remember($namespacedKey, $ttl, $callback);
        }

        $versionedKey = $this->buildVersionedKey($tag, $key);
        return Cache::remember($versionedKey, $ttl, $callback);
    }

    /**
     * Store an item with atomic locking to prevent cache stampedes.
     * Useful for expensive queries (homepage, navigation, etc).
     */
    public function rememberWithLock(string $tag, string $key, $ttl, \Closure $callback, int $lockSeconds = 10)
    {
        $namespacedKey = $this->supportsTags() ? "{$this->prefix}:{$key}" : $this->buildVersionedKey($tag, $key);

        // Fast retrieval check without lock
        if ($this->supportsTags()) {
            $value = Cache::tags([$tag])->get($namespacedKey);
        } else {
            $value = Cache::get($namespacedKey);
        }
        
        if (! is_null($value)) {
            return $value;
        }

        $lockKey = "lock:{$namespacedKey}";
        
        try {
            // Attempt to acquire lock, blocking for up to $lockSeconds
            $lock = Cache::lock($lockKey, $lockSeconds);
            if ($lock->block($lockSeconds)) {
                try {
                    // Double check after acquiring lock
                    if ($this->supportsTags()) {
                        $value = Cache::tags([$tag])->get($namespacedKey);
                    } else {
                        $value = Cache::get($namespacedKey);
                    }

                    if (! is_null($value)) {
                        return $value;
                    }
                    
                    // Execute expensive query
                    $value = $callback();
                    
                    // Store value
                    if ($this->supportsTags()) {
                        Cache::tags([$tag])->put($namespacedKey, $value, $ttl);
                    } else {
                        Cache::put($namespacedKey, $value, $ttl);
                    }
                    
                    return $value;
                } finally {
                    $lock->release();
                }
            }
        } catch (\BadMethodCallException $e) {
            // Fallback for drivers that don't support locks
            return $this->remember($tag, $key, $ttl, $callback);
        }

        // If lock couldn't be acquired in time, just try remember as a last resort
        return $this->remember($tag, $key, $ttl, $callback);
    }

    /**
     * Invalidate a cache tag.
     */
    public function flush(string $tag): void
    {
        if ($this->supportsTags()) {
            Cache::tags([$tag])->flush();
            return;
        }

        // Increment the tag version to abandon all existing keys associated with this tag
        $versionKey = "{$this->prefix}:tag_version:{$tag}";
        
        // If the key doesn't exist yet, rememberForever will create it as 1.
        // We can just increment it, which creates it if it doesn't exist (returns 1).
        Cache::increment($versionKey);
    }

    /**
     * Build a versioned cache key.
     */
    protected function buildVersionedKey(string $tag, string $key): string
    {
        $versionKey = "{$this->prefix}:tag_version:{$tag}";
        $version = Cache::rememberForever($versionKey, fn () => 1);

        return "{$this->prefix}:{$tag}:v{$version}:{$key}";
    }
}
