<?php

declare(strict_types=1);

namespace Retry\Manager\Contract;

use Retry;

/**
 * @class Manager
 */
interface Manager
{
    /**
     * @param string $key
     * @return bool
     */
    public function exist(string $key): bool;

    /**
     * @param string $key
     * @return Retry\Contract\RetryInterface
     */
    public function get(string $key): Retry\Contract\RetryInterface;

    /**
     * @param Retry\Contract\RetryInterface $retry
     * @return void
     */
    public function acquire(Retry\Contract\RetryInterface $retry): void;

    /**
     * @param Retry\Contract\RetryInterface $retry
     * @return void
     */
    public function refresh(Retry\Contract\RetryInterface $retry): void;

    /**
     * @param string $key
     * @return void
     */
    public function release(string $key): void;
}
