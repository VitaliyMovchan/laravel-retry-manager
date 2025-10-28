<?php

namespace Retry\Contract;

/**
 * @class Retry
 */
interface RetryInterface
{
    /**
     * @return string
     */
    public function getKey(): string;

    /**
     * @return int
     */
    public function getAttempt(): int;

    /**
     * @return int
     */
    public function getLimit(): int;

    /**
     * @return int
     */
    public function getDelay(): int;

    /**
     * @param string $key
     * @return $this
     */
    public function setKey(string $key): self;

    /**
     * @param int $attempt
     * @return $this
     */
    public function setAttempt(int $attempt): self;

    /**
     * @param int $limit
     * @return $this
     */
    public function setLimit(int $limit): self;

    /**
     * @param int $delay
     * @return $this
     */
    public function setDelay(int $delay): self;

    /**
     * @return bool
     */
    public function isTriable(): bool;
}
