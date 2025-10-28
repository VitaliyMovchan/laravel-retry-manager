<?php

declare(strict_types=1);

namespace Retry\Store\Lua;

class LuaScripts
{
    /**
     * Get the Lua script to atomically acquire an instance.
     *
     * KEYS[1] - The name of the instance
     *
     * ARGV[1] - The number of the attempt
     * ARGV[2] - The max number of the attempt
     * ARGV[3] - The seconds of waiting
     *
     * @return string
     */
    public function acquireScript(): string
    {
        return <<<'LUA'
            local key = KEYS[1]

            local attempt = tonumber(ARGV[1])
            local limit   = tonumber(ARGV[2])
            local delay   = tonumber(ARGV[3])

            if redis.call('EXISTS', key) == 0 then
                -- create hash
                if redis.call('HMSET', key, 'attempt', attempt, 'limit', limit, 'delay', delay) == 1 then
                    return 1
                else
                    return 0
                end
            end

            return 1
        LUA;
    }

    /**
     * Get the Lua script to exist an instance.
     *
     * KEYS[1] - The name of the instance
     *
     * @return string
     */
    public function existScript(): string
    {
        return <<<'LUA'
            local key = KEYS[1]

            if redis.call('EXISTS', key) == 1 then
                    return 1
                else
                    return 0
                end
        LUA;
    }

    /**
     * Get the Lua script to get hash.
     *
     * KEYS[1] - The name of the instance
     *
     * @return string
     */
    public function getScript(): string
    {
        return <<<'LUA'
            local key = KEYS[1]

            -- check type
            if redis.call("TYPE", key).ok == "hash" then
                 -- return hash
                return {
                    tonumber(redis.call('HGET', key, 'attempt')),
                    tonumber(redis.call('HGET', key, 'limit')),
                    tonumber(redis.call('HGET', key, 'delay'))
                }
            else
                return {0, 0, 0}
            end
        LUA;
    }

    /**
     * Get the Lua script to atomically update a retry.
     *
     * KEYS[1] - The name of the instance
     *
     * ARGV[1] - The number of the attempt
     * ARGV[3] - The seconds of waiting
     *
     * @return string
     */
    public function refreshScript(): string
    {
        return <<<'LUA'
            local key = KEYS[1]

            local attempt = ARGV[1]
            local delay = ARGV[2]

            -- check type
            if redis.call("TYPE", key).ok == "hash" then
                -- update hash
                if redis.call('HMSET', key, 'attempt', attempt, 'delay', delay) == 1 then
                    return 1
                else
                    return 0
                end
            else
                return 0
            end
        LUA;
    }

    /**
     * Get the Lua script to atomically delete a retry.
     *
     * KEYS[1] - The name of the instance
     *
     * @return string
     */
    public function releaseScript(): string
    {
        return <<<'LUA'
            local key = KEYS[1]

            if redis.call('EXISTS', key) == 1 then
                if redis.call('DEL', key) == 1 then
                    return 1
                else
                    return 0
                end
            else
                return 0
            end
        LUA;
    }
}
