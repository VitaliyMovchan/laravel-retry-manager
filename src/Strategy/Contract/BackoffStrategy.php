<?php

declare(strict_types=1);

namespace Retry\Strategy\Contract;

/**
 * @class RetryStrategy
 */
interface BackoffStrategy
{
    /**
     * @return int
     */
    public function seconds(): int;

    /**
     * @param int $attempt
     * @return int
     */
    public function delay(int $attempt): int;
}
