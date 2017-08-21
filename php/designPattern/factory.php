<?php
/**
 * User: lwx 2017/08/21 11:09
 * Explain: 工厂模式
 *
 * ---------------------------------------------------------------------------------------------------------------
 * 意图：定义一个创建对象的接口，让其子类自己决定实例化哪一个工厂类，工厂模式使其创建过程延迟到子类进行。
 * 主要解决：主要解决接口选择的问题。
 * 何时使用：我们明确地计划不同条件下创建不同实例时。
 * 如何解决：让其子类实现工厂接口，返回的也是一个抽象的产品。
 *
 *---------------------------------------------------------------------------------------------------------------*/

# 定义上层接口
interface car
{
    public function say();
}

# 子类 实现这个接口
class bus implements car
{
    public function say()
    {
        echo '我是汽车，我可以跑80千米/小时' .PHP_EOL;
    }
}

class sportsCar implements car
{
    public function say()
    {
        echo '我是跑车，我可以跑180千米/小时' .PHP_EOL;
    }
}


# 工厂，生成基于给定信息的实体类的对象
class factory
{
    public function getCar(string $carType)
    {
        $carType = strtolower($carType);

        if ('bus' == $carType)
        {
            return new bus();
        }elseif('sportscar' == $carType)
        {
            return new sportsCar();
        }
        return null;
    }
}


# 使用该工厂，通过传递类型信息来获取实体类的对象。
class demo
{
    public function run()
    {
        # 工程类
        $factory = new factory();

        # 获取bus类
        $bus = $factory->getCar('bus');
        $bus->say();

        # 获取 sportsCar类
        $sportsCar = $factory->getCar('sportscar');
        $sportsCar->say();
    }
}

# run
$demo = new demo();
$demo->run();





