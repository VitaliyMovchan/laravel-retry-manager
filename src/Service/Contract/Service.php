<?php

declare(strict_types=1);

namespace Retry\Service\Contract;

use Closure;
use Illuminate;
use Retry;

/**
 * @class Service
 */
interface Service
{
    /**
     * @param string $name
     * @param Closure $callback
     * @param Illuminate\Support\Collection $options
     * @return bool
     */
    public function try(string $name, Closure $callback, Illuminate\Support\Collection $options): bool;

    /**
     * @param string $key
     * @param Illuminate\Support\Collection $options
     * @return Retry\Retry
     */
    public function create(string $key, Illuminate\Support\Collection $options): Retry\Retry;

    /**
     * @param int $delay
     * @return void
     */
    public function block(int $delay): void;
}
