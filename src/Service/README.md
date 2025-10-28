Create service
```
    $retryService = new RetryService(
        new RetryFactory(),
        new ExponentialStrategy(2),
        new RetryManager(new RetryRepository(new MemoryStore(collect())))
    );
    
    $retryService = new RetryService(
        new RetryFactory(),
        new ExponentialStrategy(2),
        new RetryManager(new RetryRepository(new RedisStore(Redis::connection()->client(), new LuaScripts())))
      );
```

Try
```
    $retryService->try(
        'test_retry_hash',
        function () {
            throw new \Exception('retry hash failed');
        },
        collect(['limit' => 3])
    );
```
