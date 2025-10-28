<?php

declare(strict_types=1);

namespace Retry;

use Retry\Contract\RetryInterface;

/**
 * @final
 * @class Retry
 * @implements RetryInterface
 */
final class Retry implements RetryInterface
{
    /**
     * @var string
     */
    private string $key = '';

    /**
     * @var int
     */
    private int $attempt = 0;

    /**
     * @var int
     */
    private int $limit = 0;

    /**
     * @var int
     */
    private int $delay = 0;

    /**
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * @return int
     */
    public function getAttempt(): int
    {
        return $this->attempt;
    }

    /**
     * @return int
     */
    public function getLimit(): int
    {
        return $this->limit;
    }

    /**
     * @return int
     */
    public function getDelay(): int
    {
        return $this->delay;
    }

    /**
     * @param string $key
     * @return $this
     */
    public function setKey(string $key): self
    {
        $this->key = $key;

        return $this;
    }

    /**
     * @param int $attempt
     * @return $this
     */
    public function setAttempt(int $attempt): self
    {
        $this->attempt = $attempt;

        return $this;
    }

    /**
     * @param int $limit
     * @return $this
     */
    public function setLimit(int $limit): self
    {
        $this->limit = $limit;

        return $this;
    }

    /**
     * @param int $delay
     * @return $this
     */
    public function setDelay(int $delay): self
    {
        $this->delay = $delay;

        return $this;
    }

    /**
     * @return bool
     */
    public function isTriable(): bool
    {
        return $this->attempt < $this->limit;
    }
}
