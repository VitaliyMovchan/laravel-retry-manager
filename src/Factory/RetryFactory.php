<?php

declare(strict_types=1);

namespace Retry\Factory;

use Retry;

/**
 * @class RetryFactory
 * @implements Contract\Factory
 */
class RetryFactory implements Retry\Factory\Contract\Factory
{
    /**
     * @param string $key
     * @param int $attempt
     * @param int $limit
     * @param int $delay
     * @return Retry\Contract\RetryInterface
     */
    public function make(string $key, int $attempt, int $limit, int $delay): Retry\Contract\RetryInterface
    {
        return (new Retry\Retry())
            ->setKey($key)
            ->setAttempt($attempt)
            ->setLimit($limit)
            ->setDelay($delay);
    }
}
