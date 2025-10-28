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

Get retry instance
```
 $repository->get('test_retry_hash');

 collection['attempt' => 0, 'limit' => 0, 'delay' => 0]
```

Acquire retry instance
```
 $repository->acquire('test_retry_hash', 1, 8, 2);

 collection['attempt' => 1, 'limit' => 8, 'delay' => 2]
```

Refresh attempt
```
 $repository->refresh('test_retry_hash', 2, 4);

 collection['attempt' => 2, 'limit' => 8, 'delay' => 4]
```

Release retry instance
```
 $repository->release('test_retry_hash');

 collection['attempt' => 0, 'limit' => 0, 'delay' => 0]
```
