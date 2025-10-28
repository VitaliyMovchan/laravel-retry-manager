<?php

namespace Retry\Repository\Contract;

use Illuminate;

interface Repository
{
    /**
     * @param string $key
     * @return bool
     */
    public function exist(string $key): bool;

    /**
     * @param string $key
     * @return Illuminate\Support\Collection
     */
    public function get(string $key): Illuminate\Support\Collection;

    /**
     * @param string $key
     * @param int $attempt
     * @param int $limit
     * @param int $delay
     * @return void
     */
    public function acquire(string $key, int $attempt, int $limit, int $delay): void;

    /**
     * @param string $key
     * @param int $attempt
     * @param int $delay
     * @return void
     */
    public function refresh(string $key, int $attempt, int $delay): void;

    /**
     * @param string $key
     * @return void
     */
    public function release(string $key): void;
}
