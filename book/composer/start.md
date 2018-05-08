## 一. 参考
    http://docs.phpcomposer.com/00-intro.html#Using-Composer
    
  
## 二. 安装Composer 

### windows
#### 1 使用安装程序
 - 下载并且运行 [Composer-Setup.exe](https://getcomposer.org/Composer-Setup.exe '下载composer')

#### 2 使用命令安装    

    ```
    C:\Users\username>cd C:\bin
    C:\bin>php -r "readfile('https://getcomposer.org/installer');" | php
    ```
    
    
### linux

#### 1 局部安装

    ```
    curl -sS https://getcomposer.org/installer | php
    php -r "readfile('https://getcomposer.org/installer');" | php
    curl -sS https://getcomposer.org/installer | php -- --install-dir=bin
    ```
 
#### 2 全局安装

    ```
    curl -sS https://getcomposer.org/installer | php
    mv composer.phar /usr/local/bin/composer
    ```
    


## 三. 使用Composer
 1. 建立composer.json依赖文件
 2. 下载依赖
    ```
    php composer.phar install
    ```

## 四. 自动加载
加载 Composer 下载的库中所有的类文件

    ```
    require 'vendor/autoload.php';
    ```

