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
 * 优点：   1、一个调用者想创建一个对象，只要知道其名称就可以了。
 *          2、扩展性高，如果想增加一个产品，只要扩展一个工厂类就可以。
 *          3、屏蔽产品的具体实现，调用者只关心产品的接口。
 * 缺点：每次增加一个产品时，都需要增加一个具体类和对象实现工厂，使得系统中类的个数成倍增加，在一定程度上增加了系统的复杂度，
 *       同时也增加了系统具体类的依赖。这并不是什么好事。
 *
 * 举例：汽车 -> 巴士|跑车
 *---------------------------------------------------------------------------------------------------------------*/

# 定义上层接口
interface ICar
{
    public function say();
}

# 子类 实现这个接口
class Bus implements ICar
{
    public function say()
    {
        echo '我是汽车，我可以跑80千米/小时' .PHP_EOL;
    }
}

class SportsCar implements ICar
{
    public function say()
    {
        echo '我是跑车，我可以跑180千米/小时' .PHP_EOL;
    }
}


# 工厂，生成基于给定信息的实体类的对象
class Factory
{
    public function getCar(string $carType): ICar
    {
        $carType = strtolower($carType);

        if ('bus' == $carType)
        {
            return new Bus();
        }elseif('sportscar' == $carType)
        {
            return new SportsCar();
        }
        return null;
    }
}


# 使用该工厂，通过传递类型信息来获取实体类的对象。
class Demo
{
    public function run()
    {
        # 生产类
        $factory = new Factory();

        # 获取bus类
        $bus = $factory->getCar('bus');
        $bus->say();

        # 获取 sportsCar类
        $sportsCar = $factory->getCar('sportscar');
        $sportsCar->say();
    }
}

# run
$demo = new Demo();
$demo->run();





