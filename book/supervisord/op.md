### 启动服务
```
supervisord -c /etc/supervisord.conf
service supervisord start
```

### supervisorctl

- 进入 supervisorctl 的 shell 交互界面
```
supervisorctl   #有授权，按提示输入user|pass 
```

- 启动|停止|重启|查看日志|重新加载 某个进程(program_name=你配置中写的程序名称)|所有程序
```
supervisorctl start|stop|restart|tail -f program_name|all

```

- 帮助|状态|更新配置(不重启)|重新加载配置文件（需重启服务）|退出
```
supervisorctl help|status|update|reload|exit
```

- web管理 9001端口

- 日志
```
tail -f  /tmp/supervisord.log
```



