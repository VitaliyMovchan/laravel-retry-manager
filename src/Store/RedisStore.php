<?php

declare(strict_types=1);

namespace Retry\Store;

use Redis;
use RedisException;
use Retry;

/**
 * @final
 * @readonly
 * @class RedisStore
 * @implements Contract\Store
 */
final readonly class RedisStore implements
    Contract\Store,
    Contract\PersistentStore
{
    /**
     * @param Redis $client
     * @param Retry\Store\Lua\LuaScripts $scripts
     */
    public function __construct(
        private Redis $client,
        private Retry\Store\Lua\LuaScripts $scripts
    ) {
    }

    /**
     * @param string $key
     * @return bool
     * @throws RedisException
     *
     * @link https://redis.io/commands/eval
     */
    public function exist(string $key): bool
    {
        return $this->client->eval(
            $this->scripts->existScript(),
            [$key],
            1
        ) === 1;
    }

    /**
     * @param string $key
     * @return array
     * @throws RedisException
     *
     * @link https://redis.io/commands/eval
     */
    public function get(string $key): array
    {
        $result = $this->client->eval(
            $this->scripts->getScript(),
            [$key],
            1
        );

        if ($error = $this->client->getLastError()) {
            throw new RedisException($error);
        }

        return $result;
    }

    /**
     * @param string $key
     * @param int $attempt
     * @param int $limit
     * @param int $delay
     * @return void
     * @throws RedisException
     *
     * @link https://redis.io/commands/eval
     */
    public function acquire(
        string $key,
        int $attempt,
        int $limit,
        int $delay
    ): void {
        $this->client->eval(
            $this->scripts->acquireScript(),
            [$key, $attempt, $limit, $delay],
            1
        );

        if ($error = $this->client->getLastError()) {
            throw new RedisException($error);
        }
    }

    /**
     * @param string $key
     * @param int $attempt
     * @param int $delay
     * @throws RedisException
     *
     * @link https://redis.io/commands/eval
     */
    public function refresh(
        string $key,
        int $attempt,
        int $delay
    ): void {
        $this->client->eval(
            $this->scripts->refreshScript(),
            [$key, $attempt, $delay],
            1
        );

        if ($error = $this->client->getLastError()) {
            throw new RedisException($error);
        }
    }

    /**
     * @param string $key
     * @return void
     * @throws RedisException
     *
     * @link https://redis.io/commands/eval
     */
    public function release(string $key): void
    {
         $this->client->eval(
             $this->scripts->releaseScript(),
             [$key],
             1
         );

        if ($error = $this->client->getLastError()) {
            throw new RedisException($error);
        }
    }
}
