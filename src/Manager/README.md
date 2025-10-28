Create temporary memory store
```
    $store = new MemoryStore(collect());
```

Create persistent redis store
```
    $store = new RedisStore(Redis::connection()->client(), new LuaScripts());
```

Create repository
```
    $repository = new RetryRepository(store);
```

Create manager
```
    $manager = new RetryManager($repository);
```

Get retry instance
```
    $repository->get('test_retry_hash');
 
    Sxope\Infra\Retry\Retry Object
    (
        [key:Sxope\Infra\Retry\Retry:private] => test_command_hash
        [attempt:Sxope\Infra\Retry\Retry:private] => 0
        [limit:Sxope\Infra\Retry\Retry:private] => 0
        [delay:Sxope\Infra\Retry\Retry:private] => 0
    )
```

Acquire retry instance
```
    $repository->acquire(retry);
 
    $retry = (new Retry())
        ->setKey('test_command_hash')
        ->setAttempt(1)
        ->setLimit(8)
        ->setDelay(2);
    
    Sxope\Infra\Retry\Retry Object
    (
        [key:Sxope\Infra\Retry\Retry:private] => test_command_hash
        [attempt:Sxope\Infra\Retry\Retry:private] => 1
        [limit:Sxope\Infra\Retry\Retry:private] => 8
        [delay:Sxope\Infra\Retry\Retry:private] => 2
    )
```

Refresh attempt
```
    $retry = (new Retry())
        ->setKey('test_command_hash')
        ->setAttempt(2)
        ->setLimit(8)
        ->setDelay(4);
            
    $repository->refresh($retry);
 
    Sxope\Infra\Retry\Retry Object
    (
        [key:Sxope\Infra\Retry\Retry:private] => test_command_hash
        [attempt:Sxope\Infra\Retry\Retry:private] => 2
        [limit:Sxope\Infra\Retry\Retry:private] => 8
        [delay:Sxope\Infra\Retry\Retry:private] => 4
    )
```

Release instance
```
    $repository->release('test_retry_hash');

    Sxope\Infra\Retry\Retry Object
    (
        [key:Sxope\Infra\Retry\Retry:private] => test_command_hash
        [attempt:Sxope\Infra\Retry\Retry:private] => 0
        [limit:Sxope\Infra\Retry\Retry:private] => 0
        [delay:Sxope\Infra\Retry\Retry:private] => 0
    )
```
