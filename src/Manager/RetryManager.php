<?php

namespace Retry\Manager;

use Retry;

/**
 * @final
 * @readonly
 * @class RetryManager
 * @implements Retry\Manager\Contract\Manager
 */
final readonly class RetryManager implements Retry\Manager\Contract\Manager
{
    /**
     * @param Retry\Repository\RetryRepository $repository
     */
    public function __construct(
        private Retry\Repository\RetryRepository $repository,
    ) {
    }

    /**
     * @param string $key
     * @return bool
     */
    public function exist(string $key): bool
    {
        return $this->repository->exist($key);
    }

    /**
     * @param string $key
     * @return Retry\Contract\RetryInterface
     */
    public function get(string $key): Retry\Contract\RetryInterface
    {
        $fields = $this->repository->get($key);

        return (new Retry\Retry())
            ->setKey($key)
            ->setAttempt(intval($fields->get('attempt', 0)))
            ->setLimit(intval($fields->get('limit', 0)))
            ->setDelay(intval($fields->get('delay', 0)));
    }

    /**
     * @param Retry\Contract\RetryInterface $retry
     * @return void
     */
    public function acquire(Retry\Contract\RetryInterface $retry): void
    {
        $this->repository->acquire(
            $retry->getKey(),
            $retry->getAttempt(),
            $retry->getLimit(),
            $retry->getDelay(),
        );
    }

    /**
     * @param Retry\Contract\RetryInterface $retry
     * @return void
     */
    public function refresh(Retry\Contract\RetryInterface $retry): void
    {
        $this->repository->refresh(
            $retry->getKey(),
            $retry->getAttempt(),
            $retry->getDelay(),
        );
    }

    /**
     * @param string $key
     * @return void
     */
    public function release(string $key): void
    {
        $this->repository->release($key);
    }
}
