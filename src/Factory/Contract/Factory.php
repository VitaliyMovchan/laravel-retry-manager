<?php

declare(strict_types=1);

namespace Retry\Factory\Contract;

use Retry;

interface Factory
{
    /**
     * @param string $key
     * @param int $attempt
     * @param int $limit
     * @param int $delay
     * @return Retry\Contract\RetryInterface
     */
    public function make(string $key, int $attempt, int $limit, int $delay): Retry\Contract\RetryInterface;
}
