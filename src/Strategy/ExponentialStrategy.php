<?php

declare(strict_types=1);

namespace Retry\Strategy;

use Retry;

/**
 * @link http://dthain.blogspot.com/2009/02/exponential-backoff-in-distributed.html
 *
 * @final
 * @class BackoffStrategy
 * @implements Retry\Strategy\Contract\BackoffStrategy
 *
 * @private int $seconds
 */
final readonly class ExponentialStrategy implements Retry\Strategy\Contract\BackoffStrategy
{
    /**
     * @param int $seconds
     */
    public function __construct(private int $seconds)
    {
    }

    /**
     * @return int
     */
    public function seconds(): int
    {
        return $this->seconds;
    }

    /**
     * @param int $attempt
     * @return int
     */
    public function delay(int $attempt): int
    {
        return $this->seconds() * pow($this->seconds, $attempt);
    }
}
