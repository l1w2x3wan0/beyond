## 交换机类型 exchange

|    名称    |   类型    |       投递方式          |           场景              |
|---------- |:--------:|:-----------------:|:--------------------------:|
|   默认  |default(direct)|  routing key |  工作队列 循环分发任务给多个工作者 |
|   直连  | direct    |  routing key/ routing |   工作队列 循环分发任务给多个工作者 |
|   扇形  | fanout    |  广播 |   所有的N个queue中 |
|   主题  | topic     |  多播和订阅模式 |  取决于routing key和pattern的设置，只有相符合的消息才会被传到queue上 |
|   头    | headers   |  - |   - |
-------------------------------------



### 一. 直连direct [工作队列]

1.  default: 路由键routing key 与队列同名
```
生产:
$channel->queue_declare('work_task', false, true,false, false);
$channel->basic_publish($msg, '', 'work_task');

消费：
$channel->basic_consume('work_task', '', false, false, false, false, $callback);
```


2. routing key 投递

    场景：工作队列，多个客户端消费
```
生产：
$channel->exchange_declare('logs_direct', 'direct', false, false, false);
$channel->basic_publish($msg, 'logs_direct', $routing_key);

消费：
$channel->basic_consume($queue_name, '', false, true, false, false, $callback);
```



3. 多个 routing key投递

    场景： 日志系统。它能够向多个接收器广播消息
```
发布：
$channel->exchange_declare('direct_logs', 'direct', false, false, false);
$channel->basic_publish($msg, 'direct_logs', $severity);

订阅：绑定
foreach($severities as $severity) {
    $channel->queue_bind($queue_name, 'direct_logs', $severity);  err info
}
$channel->basic_consume($queue_name, '', false, true, false, false, $callback);

```


### 二. 扇型 fanout [Publish/Subscribe]
1 . 订阅/消费
```
生产:
$channel->exchange_declare('logs', 'fanout', false, false, false);
$channel->basic_publish($msg, 'logs');

消费:
$channel->queue_bind($queue_name, 'logs');      # 绑定（订阅）交换机
$channel->basic_consume($queue_name, '', false, true, false, false, $callback);
```


### 三. 主题 topic [发布/订阅]
匹配模式: 正则 */#
```
发布:
$channel->exchange_declare('topic_logs', 'topic', false, false, false);
$channel->basic_publish($msg, 'topic_logs', $routing_key);

订阅:
 $channel->queue_bind($queue_name, 'topic_logs', $binding_key);     # '*.err' '#.info.#.#'
 $channel->basic_consume($queue_name, '', false, true, false, false, $callback);
```

