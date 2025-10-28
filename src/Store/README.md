Create temporary memory store
```
 $store = new MemoryStore(collect());
```

Create persistent redis store
```
 $store = new RedisStore(Redis::connection()->client(), new LuaScripts());
```

Create persistent redis instance
```
 $redisStore = new RedisStore(Redis::connection()->client(), new LuaScripts());
```

Get retry instance
```
 $store->get('test_retry_hash');

 array[0, 0, 0]
```

Acquire retry instance
```
 $store->acquire('test_retry_hash', 1, 8, 2);

 array[1, 8, 2]
```

Refresh attempt
```
 $store->refresh('test_retry_hash', 2, 4);

 array[2, 8, 4]
```

Release retry instance
```
 $store->release('test_retry_hash');

 array[0, 0, 0]
```
