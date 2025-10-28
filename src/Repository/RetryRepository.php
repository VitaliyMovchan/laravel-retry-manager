<?php

declare(strict_types=1);

namespace Retry\Repository;

use Illuminate;
use Retry;

/**
 * @final
 * @redonly
 * @class RetryRepository
 * @implements Contract\Repository
 */
final readonly class RetryRepository implements Contract\Repository
{
    /**
     * @param Retry\Store\Contract\Store $store
     */
    public function __construct(
        private Retry\Store\Contract\Store $store
    ) {
    }

    /**
     * @param string $key
     * @return bool
     */
    public function exist(string $key): bool
    {
        return $this->store->exist($key);
    }

    /**
     * @param string $key
     * @return Illuminate\Support\Collection
     */
    public function get(string $key): Illuminate\Support\Collection
    {
        list($attempt, $limit, $delay) = $this->store->get($key);

        return collect([
            'attempt' => $attempt,
            'limit' => $limit,
            'delay' => $delay
        ]);
    }

    /**
     * @param string $key
     * @param int $attempt
     * @param int $limit
     * @param int $delay
     * @return void
     */
    public function acquire(
        string $key,
        int $attempt,
        int $limit,
        int $delay
    ): void {
        $this->store->acquire(
            key: $key,
            attempt: $attempt,
            limit: $limit,
            delay: $delay
        );
    }

    /**
     * @param string $key
     * @param int $attempt
     * @param int $delay
     * @return void
     */
    public function refresh(
        string $key,
        int $attempt,
        int $delay
    ): void {
         $this->store->refresh(
             key: $key,
             attempt: $attempt,
             delay: $delay
         );
    }

    /**
     * @param string $key
     * @return void
     */
    public function release(string $key): void
    {
        $this->store->release($key);
    }
}
