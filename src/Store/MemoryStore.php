<?php

declare(strict_types=1);

namespace Retry\Store;

use Illuminate;

/**
 * @class TemporaryStore
 * @implements Contract\Store
 */
class MemoryStore implements
    Contract\Store,
    Contract\TemporaryStore
{
    /**
     * @var Illuminate\Support\Collection
     */
    private Illuminate\Support\Collection $storage;

    /**
     * @param Illuminate\Support\Collection $storage
     */
    public function __construct(Illuminate\Support\Collection $storage)
    {
        $this->storage = $storage;
    }

    /**
     * @param string $key
     * @return bool
     */
    public function exist(string $key): bool
    {
        return $this->storage->has($key);
    }

    /**
     * @param string $key
     * @return array
     */
    public function get(string $key): array
    {
        return collect($this->storage->get(
            key: $key,
            default: [0, 0, 0]
        ))->flatten()->toArray();
    }

    /**
     * @param string $key
     * @param int $attempt
     * @param int $limit
     * @param int $delay
     * @return void
     */
    public function acquire(string $key, int $attempt, int $limit, int $delay): void
    {
        $this->storage->getOrPut(
            key: $key,
            value: fn() => [
                'attempt' => $attempt,
                'limit' => $limit,
                'delay' => $delay
            ]
        );
    }

    /**
     * @param string $key
     * @param int $attempt
     * @param int $delay
     * @return void
     */
    public function refresh(string $key, int $attempt, int $delay): void
    {
        $this->storage->put(
            key: $key,
            value: array_merge(
                (array) $this->storage->get($key),
                [
                    'attempt' => $attempt,
                    'delay' => $delay,
                ]
            )
        );
    }

    /**
     * @param string $key
     * @return void
     */
    public function release(string $key): void
    {
        $this->storage->forget([$key]);
    }
}
