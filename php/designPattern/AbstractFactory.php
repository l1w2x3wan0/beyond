<?php
/**
 * User: lwx 2017/08/22 10:42
 * Explain: 抽象工程模式
 *
 * ------------------------------------------------------------------------------------------
 * 意图：提供一个创建一系列相关或相互依赖对象的接口，而无需指定它们具体的类。
 * 主要解决：主要解决接口选择的问题。
 * 何时使用：系统的产品有多于一个的产品族，而系统只消费其中某一族的产品。
 * 如何解决：在一个产品族里面，定义多个产品。
 * 关键代码：在一个工厂里聚合多个同类产品。
 *
 * 优点：当一个产品族中的多个对象被设计成一起工作时，它能保证客户端始终只使用同一个产品族中的对象。
 * 缺点：产品族扩展非常困难，要增加一个系列的某一产品，既要在抽象的 Creator 里加代码，又要在具体的里面加代码。
 * 使用场景： 1、QQ 换皮肤，一整套一起换。 2、生成不同操作系统的程序。
 * 注意事项：产品族难扩展，产品等级易扩展。
 *
 * 举例： 商品 -> 手机 | 衣服
 *------------------------------------------------------------------------------------------*/

# 手机接口
interface IMobile
{
    public function wifi();
}

# 手机实现类 苹果
class Apple implements IMobile
{
    public function wifi()
    {
        echo 'Apple mobile\'s wifi is good' .PHP_EOL;
    }
}

# 手机实现类 三星
class Samsung implements IMobile
{
    public function wifi()
    {
        echo 'Samsung mobile\'s wifi is good' .PHP_EOL;
    }
}

# 衣服接口
interface IClothes
{
    public function type();
}

# 外套实现类
class Coat implements IClothes
{
    public function type()
    {
        echo '我是外套' . PHP_EOL;
    }
}

# 夹克实现类
class Jacket implements IClothes
{
    public function type()
    {
        echo '我是夹克' . PHP_EOL;
    }
}

# 抽象工厂
abstract class AbstractFactory
{
    # 获取手机
    abstract protected function mobile(string $mobile);

    # 获取衣服
    abstract protected function clothes(string $clothes);
}

# 手机工厂
class MobileFactory extends AbstractFactory
{
    public function mobile(string $mobile): IMobile
    {
        $module = null;
        $mobile = strtolower($mobile);

        if ('apple' == $mobile){
            echo '创建手机类的-苹果' . PHP_EOL;
            $module = new Apple();
        }elseif ('samsung' == $mobile){
            echo '创建手机类的-三星' . PHP_EOL;
            $module = new Samsung();
        }

        return $module;
    }

    public function clothes(string $clothes): IClothes
    {
        return null;
    }
}

class ClothesFactory extends AbstractFactory
{
    public function mobile(string $mobile): IMobile
    {
        return null;
    }

    public function clothes(string $clothes): IClothes
    {
        $module = null;
        $clothes = strtolower($clothes);

        if ('coat' == $clothes){
            echo '创建衣服类的-外套' . PHP_EOL;
            $module = new Coat();
        }elseif ('jacket' == $clothes){
            echo '创建衣服类的-夹克' . PHP_EOL;
            $module = new Jacket();
        }

        return $module;
    }
}

# 工厂创造/生成器
class FactoryProducer
{
    static public function getFactory(string $factoryName)
    {
        $factoryName = strtolower($factoryName);
        $factory = null;

        if ('mobile' == $factoryName) {
            echo '创建手机工厂' . PHP_EOL;
            $factory = new MobileFactory();
        }elseif ('clothes' == $factoryName) {
            echo '创建衣服工厂' . PHP_EOL;
            $factory = new ClothesFactory();
        }

        return $factory;
    }
}


class Demo
{
    public function run()
    {
        # 获取不同的工厂
        $mobileFactory  = FactoryProducer::getFactory('mobile');
        $clothesFactory = FactoryProducer::getFactory('clothes');

        # 由不同工厂，通过传递的信息，获取实体类的对象
        $apple      = $mobileFactory->mobile('apple');
        $samsung    = $mobileFactory->mobile('samsung');

        $coat   = $clothesFactory->clothes('coat');
        $jacket = $clothesFactory->clothes('jacket');

        # 实体类
        $apple->wifi();
        $samsung->wifi();
        $coat->type();
        $jacket->type();
    }
}

$demo = new Demo();
$demo->run();


