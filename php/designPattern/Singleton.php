<?php
/**
 * User: lwx 2017/08/22 14:59
 * Explain: 单例模式
 *
 * 意图：保证一个类仅有一个实例，并提供一个访问它的全局访问点。
 * 主要解决：一个全局使用的类频繁地创建与销毁。
 * 何时使用：当您想控制实例数目，节省系统资源的时候。
 * 如何解决：判断系统是否已经有这个单例，如果有则返回，如果没有则创建。
 * 关键代码：构造函数是私有的。
 */

class Singleton
{
    # 私有静态变量保存全局实例
    private static $instance = null;

    # 私有构造函数，防止外界直接实例化对象
    private function __construct()
    {
        echo '我在初始化' . PHP_EOL;
    }

    # 静态方法，外界统一入口
    public static function getInstance()
    {
        if (!(self::$instance instanceof self)){
            self::$instance = new self();
        }
        return self::$instance;
    }

    # 私有克隆函数，防止外界克隆对象
    private function __clone()
    {
        throw new Exception('Clone is not allow!',E_USER_ERROR);
    }
}

class Demo
{
    public function run()
    {
        $s1 = Singleton::getInstance();
        $s2 = Singleton::getInstance();
        var_dump($s1);
        var_dump($s2);
        clone $s1;
    }
}

$demo = new Demo();
$demo->run();