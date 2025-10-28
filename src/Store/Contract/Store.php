<?php

declare(strict_types=1);

namespace Retry\Store\Contract;

/**
 * @class Store
 */
interface Store
{
    /**
     * @param string $key
     * @return bool
     */
    public function exist(string $key): bool;

    /**
     * @param string $key
     * @return array
     */
    public function get(string $key): array;

    /**
     * @param string $key
     * @param int $attempt
     * @param int $limit
     * @param int $delay
     */
    public function acquire(string $key, int $attempt, int $limit, int $delay): void;

    /**
     * @param string $key
     * @param int $attempt
     * @param int $delay
     */
    public function refresh(string $key, int $attempt, int $delay): void;

    /**
     * @param string $key
     */
    public function release(string $key): void;
}
