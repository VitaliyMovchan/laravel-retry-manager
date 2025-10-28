<?php

declare(strict_types=1);

namespace Retry\Service;

use Closure;
use Exception;
use Illuminate;
use Retry;

/**
 * @final
 * @readonly
 * @class RetryService
 * @implements Retry\Service\Contract\Service
 */
final readonly class RetryService implements Retry\Service\Contract\Service
{
    /**
     * @param Retry\Factory\Contract\Factory $factory
     * @param Retry\Strategy\Contract\BackoffStrategy $strategy
     * @param Retry\Manager\Contract\Manager $manager
     */
    public function __construct(
        private Retry\Factory\Contract\Factory $factory,
        private Retry\Strategy\Contract\BackoffStrategy $strategy,
        private Retry\Manager\Contract\Manager $manager,
    ) {
    }

    /**
     * @param string $name
     * @param Closure $callback
     * @param Illuminate\Support\Collection $options
     * @return bool
     */
    public function try(string $name, Closure $callback, Illuminate\Support\Collection $options): bool
    {
        if ($this->manager->exist($name) === false) {
            $this->manager->acquire($this->create($name, $options));
        }

        $retry = $this->manager->get($name);

        while ($retry->isTriable()) {
            try {
                // Increase attempt
                $retry->setAttempt($retry->getAttempt() + 1);
                $retry->setDelay($this->strategy->delay($retry->getAttempt()));

                $this->manager->refresh($retry);
                $callback();
                $this->manager->release($name);

                return true;
            } catch (Exception $exception) {
                Illuminate\Support\Facades\Log::error('An error occurred while try to execute', [
                    'name' => $name,
                    'message' => $exception->getMessage(),
                ]);

                Illuminate\Support\Facades\Log::warning('Retry acquired after waiting an interval', [
                    'name' => $name,
                    'attempts' => $retry->getAttempt(),
                    'limit' => $retry->getLimit(),
                    'delay' => $retry->getDelay(),
                ]);

                // Retry acquired after waiting an interval...
                $this->block($retry->getDelay());
            }
        }

        Illuminate\Support\Facades\Log::error('The number of attempts has been exceeded', [
            'name' => $name,
        ]);

        $this->manager->release($name);

        return false;
    }

    /**
     * @param string $key
     * @param Illuminate\Support\Collection $options
     * @return Retry\Retry
     */
    public function create(string $key, Illuminate\Support\Collection $options): Retry\Retry
    {
        return $this->factory->make(
            key: $key,
            attempt: $options->get('attempt', 0),
            limit: $options->get('limit', 8),
            delay: $this->strategy->delay($options->get('attempt', 0))
        );
    }

    /**
     * @param int $delay
     * @return void
     */
    public function block(int $delay): void
    {
        Illuminate\Support\Sleep::for($delay)->seconds();
    }
}
