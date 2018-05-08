## PhpAmqpLib主要函数说明


1. 创建链接

    ```
    $connection =  new AMQPStreamConnection($host,$port,$user,$password,$vhost);
    ```


2. 创建通道

    ```
    $channel = $connection->channel($channel_id = null)
    ```
    
    
3. 信道里创建交换器

    ```
    $channel->exchange_declare(
            $exchange,              # 名称
            $type,                  # 类型
            $passive = false,       # 被动模式
            $durable = false,       # 持久化，消息代理重启后，交换机是否还存在
            $auto_delete = true,    # 当所有与之绑定的消息队列都完成了对此交换机的使用后，删掉它
            $internal = false,      # 内部的
            $nowait = false,
            $arguments = null,      # 依赖代理本身
            $ticket = null          # 凭证
        )
        rpc 
        # $passive  false
        # durable false
        # auto_detlete false 
    ```

    
4. 声明消费者队列

    1) 指定队列
    
    ```
    $queue = $channel->queue_declare(
             $queue = '',               # 队列名称。 如不填，系统自动分配名字 
             $passive = false,          # 被动模式
             $durable = false,          # 持久化
             $exclusive = false,        # 独占 
                                        1.只对首次声明它的连接（Connection）可见
                                        2.会在其连接断开的时候自动删除，不管这个队列是否被声明成持久性的（Durable =true)。
             $auto_delete = true,       # 自动删除
             $nowait = false,           # 不等待
             $arguments = null,         # 参数
             $ticket = null             # 凭证
             )
    ```
   
    2) 随机队列
     
    a) 非持久化队列,RabbitMQ退出或者崩溃时，该队列就不存在
    
    ```
    list($queue_name, ,) = $channel->queue_declare("", false, false, false, false)    
    ```
    b)  持久化队列（需要显示声明，第三个参数要设置为true），保存到磁盘，但不一定完全保证不丢失信息，因为保存总是要有时间的。
    
    ```
    list($queue_name, ,) = $channel->queue_declare("ex_queue", false, false, true, false)
    ```


5. 发布消息

    1) 创建消息
    
    ```
    原型：
    __construct($body = '', $properties = array())
    protected static $propertyDefinitions = array(
            'content_type' => 'shortstr',               # 消息内容类型
            'content_encoding' => 'shortstr',           # 消息编码
            'application_headers' => 'table_object',    # 头
            'delivery_mode' => 'octet',                 # 持久化
            'priority' => 'octet',
            'correlation_id' => 'shortstr',             # 相关性ID
            'reply_to' => 'shortstr',                   # 返回队列
            'expiration' => 'shortstr',
            'message_id' => 'shortstr',
            'timestamp' => 'timestamp',
            'type' => 'shortstr',
            'user_id' => 'shortstr',
            'app_id' => 'shortstr',
            'cluster_id' => 'shortstr',
        );
        
    $msg = new AMQPMessage(
                (string) $data,
                [
                    'correlation_id'    => $this->corr_id,
                    'reply_to'          => $this->callback_queue,
                    # 'delivery_mode'     =>2, // 持久化
                ]
            );
    ```
    
    2) 发布
    
    ```
    function basic_publish(
            $msg,                   # 消息
            $exchange = '',         # 交换机
            $routing_key = '',      # 路由key
            $mandatory = false,     # 托管
            $immediate = false,     # 立即
            $ticket = null
        )
    
    $this->channel->basic_publish($msg, $this->exchange, $routing_key);
    ```


6. 绑定队列到交换机

    ```
    $channel->queue_bind(
        $queue,             # 队列名称 
        $exchange,          # 交换机 
        $routing_key = '',  # 路由key。 默认为空，表示对该交换机所有消息感兴趣；如果值不为空，则该队列只对该类型的消息感兴趣（除了fanout交换机以外）；
        $nowait = false, 
        $arguments = null, 
        $ticket = null
    );
    ```
 

7. 消费消息

    1) 消息分发/消息预取
    
    ```
    #$channel->basic_qos(
        $prefetch_size,     # 预取消息大小 
        $prefetch_count,    # 预取消息数量
        $a_global           # 全局
    );
    
    告诉RabbitMQ，再同一时刻，不要发送超过1条消息给一个工作者（worker），直到它已经处理了上一条消息并且作出了响应
    $channel->basic_qos(null, 1, null); 
    ```
    
    2) 消费
    
    ```
    $channel->basic_consume(
        $queue = '',            # 队列名称
        $consumer_tag = '',     # 消费者标签？
        $no_local = false,
        $no_ack = false,        # 消息确认 false 时，表示进行ack应答，确保消息已经处理
        $exclusive = false,     #
        $nowait = false,        # 
        $callback = null,       # 回掉函数， 传入消息参数
        $ticket = null,
        $arguments = array()
        );
    ```
    
    3) 回调
    
    ```
    $callback = function($msg){
      echo " [x] Received ", $msg->body, "\n";
      sleep(substr_count($msg->body, '.'));
      echo " [x] Done", "\n";
    };
    ```
    
    4) 消息确认
    
     当no_ack=false时， 需要在回掉函数里写消息确认，否则可能出现内存不足情况
     ```
     basic_ack(
         $delivery_tag,     # 消息tag 
         $multiple = false  # 是否多个消息
         )
     
     $msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);
     ```
    
    5) 监听消息，一有消息就消费
    
    ```
    while(count($channel->callbacks)) {
        $channel->wait();
    }
    ```


## 主题

### 消息确定

    ```
    只在消费端设置:
    1.返回确定的消息 （这是在执行完成后， 而且是channel通道 在调用）  delivery_tag 交付tag
        $msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);
      
    2.开启消息确定模式 第4个参数： false
        $channel->basic_consume('work_task', '', false, false, false, false, $callback);
    ```

### 公平调度

    ```
    # 公平调度：在确认前一个消息之前，不要向这个消费者发送新的消息(消费端设置:)
    $channel->basic_qos(null, 1, null);
    ```

### 持久化

 - 消息队列的持久化

    ```
    生产和消费都需要设置:
    # 设置队列 持久化 3->true  需要生产和消费端都设置 （rabbit重启也不丢失数据）
    $channel->queue_declare('work_task', false, true,false, false);
    ```

  - 消息的持久化
    ```
    # 消息标记为持久化（配合队列的持久化设置）， delivery = 2
    # 由于缓存写入磁盘有时间差，所有可能会有消息丢失， 解决： publisher confirms
    ['delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT]
    ```
    
### 几种应用

1. 发布订阅(广播)
```
生产端:
    1.定义交换机 fanout-广播  
    $channel->exchange_declare('logs', 'fanout', false, false, false);
    
    2.发送
    $channel->basic_publish($msg, 'logs'); # 第三个参数的routing_key 为空


客户端:
    1.定义交换机 fanout
    $channel->exchange_declare('logs', 'fanout', false, false, false);
    
    2.创建匿名队列
    list($queue_name, ,) = $channel->queue_declare("");
    
    3.绑定队列到交换机，交换机就会发现消息到队列，后面就消费队列
    $channel->queue_bind($queue_name, 'logs');
    
    4.消费队列就行
    $channel->basic_consume($queue_name, '', false, true, false, false, $callback);
```

2. 发布订阅（路由）

```
与广播的区别:

1.定义交换机为 direct 类型
$channel->exchange_declare('direct_logs', 'direct', false, false, false); # 第2个参数

2.发生消息时候，需知道路由
$channel->basic_publish($msg, 'direct_logs', $severity);  #第三个参数

3.客户端绑定队列时，需绑定路由
$channel->queue_bind($queue_name, 'direct_logs', $severity);
```

3. 主题 (topics)

```
1.定义交换机为 topics 类型 / 其它的与direct都相同
$channel->exchange_declare('direct_logs', 'topics', false, false, false); # 第2个参数


注意：

1.通配符:
*(星号) 可以代替一个单词
#(哈希) 可以匹配0个或多个单词

2.与其它的比较:
topic exchange 很强大，并且表现得和其他类型交换一样 
当队列与 “#” 绑定时，它将接收所有消息而不管routing key的值，如同 fanout exchange 
当不使用 “*” 和 “#” 特殊字符时，topic exchange 如同 direct exchange
```