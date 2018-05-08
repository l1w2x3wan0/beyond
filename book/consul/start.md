## 
- https://www.jianshu.com/p/1d36a6277c3b
- http://izheyi.com/2017/12/26/Consul-Key-Value%E5%92%8CWeb-UI/

```linux
# agent server
consul agent -server -bootstrap-expect 1 -data-dir /tmp/consul-node=centos73 -bind=192.168.154.136 -config-dir /etc/consul.d/

consul agent -server -bootstrap-expect 1 -data-dir /tmp/consul-node=centos73 -bind=192.168.154.137 -config-dir /etc/consul.d -join=192.168.154.136
# agent client
consul agent -data-dir /tmp/consul -node=centos138 -bind=192.168.154.138  -enable-script-checks=true -config-dir /etc/consul.d/ -join=192.168.154.136
# join agent client
consul join 192.168.154.138

# windows ui
consul agent -data-dir "D:\service\consul\data" -node=windows -bind=192.168.154.1   -config-dir "D:\service\consul\conf" -ui -join=192.168.154.136


# 健康检查
curl http://localhost:8500/v1/health/state/critical

# dns 查询 
dig @127.0.0.1 -p 8600 centos138.node.consul

# key
curl -v http://localhost:8500/v1/kv/?recurse

curl -X PUT -d 'test' http://localhost:8500/v1/kv/web/key1
curl -X PUT -d 'test' http://localhost:8500/v1/kv/web/key2?flags=42
curl -X PUT -d 'test' http://localhost:8500/v1/kv/web/sub/key3

curl http://localhost:8500/v1/kv/web/key1

curl -X DELETE http://localhost:8500/v1/kv/web/sub?recurse

curl -X PUT -d 'newval' http://localhost:8500/v1/kv/web/key1?cas=1201
```


```
-advertise：通知展现地址用来改变我们给集群中的其他节点展现的地址，一般情况下-bind地址就是展现地址
-bootstrap：用来控制一个server是否在bootstrap模式，在一个datacenter中只能有一个server处于bootstrap模式，当一个server处于bootstrap模式时，可以自己选举为raft leader。
-bootstrap-expect：在一个datacenter中期望提供的server节点数目，当该值提供的时候，consul一直等到达到指定sever数目的时候才会引导整个集群，该标记不能和bootstrap公用
-bind：该地址用来在集群内部的通讯，集群内的所有节点到地址都必须是可达的，默认是0.0.0.0
-client：consul绑定在哪个client地址上，这个地址提供HTTP、DNS、RPC等服务，默认是127.0.0.1
-config-file：明确的指定要加载哪个配置文件
-config-dir：配置文件目录，里面所有以.json结尾的文件都会被加载
-data-dir：提供一个目录用来存放agent的状态，所有的agent允许都需要该目录，该目录必须是稳定的，系统重启后都继续存在
-dc：该标记控制agent允许的datacenter的名称，默认是dc1
-encrypt：指定secret key，使consul在通讯时进行加密，key可以通过consul keygen生成，同一个集群中的节点必须使用相同的key
-join：加入一个已经启动的agent的ip地址，可以多次指定多个agent的地址。如果consul不能加入任何指定的地址中，则agent会启动失败，默认agent启动时不会加入任何节点。
-retry-join：和join类似，但是允许你在第一次失败后进行尝试。
-retry-interval：两次join之间的时间间隔，默认是30s
-retry-max：尝试重复join的次数，默认是0，也就是无限次尝试
-log-level：consul agent启动后显示的日志信息级别。默认是info，可选：trace、debug、info、warn、err。
-node：节点在集群中的名称，在一个集群中必须是唯一的，默认是该节点的主机名
-protocol：consul使用的协议版本
-rejoin：使consul忽略先前的离开，在再次启动后仍旧尝试加入集群中。
-server：定义agent运行在server模式，每个集群至少有一个server，建议每个集群的server不要超过5个
-syslog：开启系统日志功能，只在linux/osx上生效
-pid-file:提供一个路径来存放pid文件，可以使用该文件进行SIGINT/SIGHUP(关闭/更新)agent
```