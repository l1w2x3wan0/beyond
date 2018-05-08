
# 服务安装
                          
## windows
### 下载
1. 安装Erlang  http://www.erlang.org/downloads
2. RabbitMQ http://www.rabbitmq.com/download.html
3. 配置
 - 激活 RabbitMQ's Management Plugin
     ```shell
     sbin\rabbitmq-plugins.bat enable rabbitmq_management
     ```
 - 启动
     ```
     net stop RabbitMQ && net start RabbitMQ
     ```
 4. 管理
     - 管理控制台
        - http://localhost:15672 访问Rabbit Mq的管理控制台
    
     - 创建用户，密码，绑定角色
         ```
         rabbitmqctl.bat list_users
         rabbitmqctl.bat add_user username password
         rabbitmqctl.bat set_user_tags username administrator
         rabbitmqctl.bat  set_user_tags  username tag1 tag2 ...
         rabbitmqctl change_password userName newPassword
         rabbitmqctl.bat delete_user username
         ```
         
     - 权限设置
        
        1.设置用户权限
        ```
        rabbitmqctl  set_permissions  -p  VHostPath  User  ConfP  WriteP  ReadP
        ```
        2 查看(指定hostpath)所有用户的权限信息
        ```
        rabbitmqctl  list_permissions  [-p  VHostPath]
        ```
        3 查看指定用户的权限信息
        ```
        rabbitmqctl  list_user_permissions  User
        ```
        4  清除用户的权限信息
        ```
        rabbitmqctl  clear_permissions  [-p VHostPath]  User
       ```



### windows扩展

#### 下载
- url https://pecl.php.net/package/amqp

#### 安装

安装方法如下：

    1、复制php_amqp.dll到php/ext， 如我的放到 C:\wamp\bin\php\php5.5.12\ext目录下
    2、php.ini中添加如下代码(使用wampserver的注意php.ini的位置，因为会存在两个地方有php.ini的文件）
        extension=php_amqp.dll
    3、复制rabbitmq.1.dll到php目录 如我的放到 C:\wamp\bin\php\php5.5.12 目录下
    4、修改apache配置文件httpd.conf，添加如下
        LoadFile "rabbitmq.1.dll的文件路径"，如我的配置如下

- 安装参考 http://www.rabbitmq.com/install-windows-manual.html
- 权限内容参考 http://www.rabbitmq.com/man/rabbitmqctl.1.man.html
- 权限命令摘自 https://my.oschina.net/hncscwc/blog/262246


## linux

### 安装

1.依赖文件：
```
yum install gcc glibc-devel make ncurses-devel openssl-devel xmlto
yum install ncurses-devel unixODBC unixODBC-devel
yum -y install make gcc gcc-c++ kernel-devel m4 ncurses-devel openssl-devel unixODBC-devel
yum -y install socat
```


2.down
```
wget http://www.rabbitmq.com/releases/erlang/erlang-19.0.4-1.el7.centos.x86_64.rpm
wget http://www.rabbitmq.com/releases/rabbitmq-server/v3.6.6/rabbitmq-server-3.6.6-1.el7.noarch.rpm
```

3.install
```
rpm -ivh erlang-19.0.4-1.el7.centos.x86_64.rpm
rpm -ivh rabbitmq-server-3.6.6-1.el7.noarch.rpm 
```

4.manager
```
/sbin/service rabbitmq-server stop/start/status/restart
cat /var/log/rabbitmq/rabbit\@centos73.log 
vim /etc/rabbitmq/rabbitmq.config   [{rabbit, [{loopback_users, []}]}].
```

5.plug
```
/sbin/rabbitmq-plugins enable rabbitmq_management  
```

http://ip:15672 使用guest,guest 进行登陆了

参考：
- http://blog.csdn.net/hongchenlingtian/article/details/54378405
- RabbitMQ+PHP 消息队列环境配置 http://www.cnblogs.com/chunguang/p/5634342.html




### php扩展
1. 安装rabbitmq-c依赖包
```
yum install libtool autoconf
```

2.安装rabbitmq-c
下载：https://github.com/alanxz/rabbitmq-c/releases/tag/v0.8.0
```
wget https://github.com/alanxz/rabbitmq-c/releases/download/v0.8.0/rabbitmq-c-0.8.0.tar.gz
tar -xzvf rabbitmq-c-0.8.0.tar.gz 
cd rabbitmq-c-0.8.0
autoreconf -i
./configure --prefix=/usr/local/rabbitmq-c
make && make install
```

3.rabbitqm
```
wget https://pecl.php.net/get/amqp-1.9.3.tgz
tar xzvf amqp-1.9.3.tgz
cd amqp-1.9.3
/usr/local/php7/bin/phpize
./configure --with-php-config=/usr/local/php7/bin/php-config --with-amqp --with-librabbitmq-dir=/usr/local/rabbitmq-c
make
make install
```

4.编辑php.ini
```
vim /usr/local/php7/etc/php.ini
 extension=amqp.so
```

5.重启php-fpm
```
systemctl restart php-fpm
```

6.检查
```
php -i |grep amqp
php -m |grep amqp
```

参考： https://www.cnblogs.com/spectrelb/p/6856246.html



# 使用
## 入门

参考：
 - 使用php-amqplib连接rabbitMQ 学习笔记及总结 http://www.cnblogs.com/oyxp/p/7376733.html
 - 官方 http://www.rabbitmq.com/tutorials/tutorial-one-php.html
 - 官方翻译 http://blog.csdn.net/demon3182/article/category/7108232
 - AMQP 0.9.1 模型解析 https://rabbitmq.shujuwajue.com/AMQP/AMQP_0-9-1_Model_Explained.html
 - 入门 http://blog.csdn.net/column/details/rabbitmq.html