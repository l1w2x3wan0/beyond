

### program 配置示例
```
[program:orders_consume]
command=/usr/local/bin/php /home/max/jobs/short orders_consume
numprocs=1                    ; 启动几个进程
autostart=true                ; 随着supervisord的启动而启动
autorestart=true              ; 自动重启。。当然要选上了
startretries=10               ; 启动失败时的最多重试次数
exitcodes=0                   ; 正常退出代码（是说退出代码是这个时就不再重启了吗？待确定）
stopsignal=KILL               ; 用来杀死进程的信号
stopwaitsecs=10               ; 发送SIGKILL前的等待时间
redirect_stderr=true          ; 重定向stderr到stdout
stdout_logfile=/home/max/jobs/logs/orders_consume.log ; 子进程标准输出流日志路径(path|AUTO|none)
stdout_logfile_maxbytes=1073741824 ;
```