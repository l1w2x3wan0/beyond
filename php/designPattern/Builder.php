<?php
/**
 * User: lwx 2017/08/22 15:51
 * Explain:建造者模式
 *
 * ------------------------------------------------------------------------------------------
 * 意图：将一个复杂的构建与其表示相分离，使得同样的构建过程可以创建不同的表示。
 * 主要解决：主要解决在软件系统中，有时候面临着"一个复杂对象"的创建工作，其通常由各个部分的子对象用一定的算法构成；
 *          由于需求的变化，这个复杂对象的各个部分经常面临着剧烈的变化，但是将它们组合在一起的算法却相对稳定。
 * 何时使用：一些基本部件不会变，而其组合经常变化的时候。
 * 如何解决：将变与不变分离开。
 * 关键代码：建造者：创建和提供实例，导演：管理建造出来的实例的依赖关系。
 * 应用实例： 1、去肯德基，汉堡、可乐、薯条、炸鸡翅等是不变的，而其组合是经常变化的，生成出所谓的"套餐"。
 * 优点： 1、建造者独立，易扩展。 2、便于控制细节风险。
 * 缺点： 1、产品必须有共同点，范围有限制。 2、如内部变化复杂，会有很多的建造类。
 * 使用场景： 1、需要生成的对象具有复杂的内部结构。 2、需要生成的对象内部属性本身相互依赖。
 * 注意事项：与工厂模式的区别是：建造者模式更加关注与零件装配的顺序。
 *
 * ------------------------------------------------------------------------------------------*/

/**
 * 实现
 * 我们假设一个快餐店的商业案例，其中，一个典型的套餐可以是一个汉堡（Burger）和一杯冷饮（Cold drink）。
 * 汉堡（Burger）可以是素食汉堡（Veg Burger）或鸡肉汉堡（Chicken Burger），它们是包在纸盒中。冷饮（Cold drink）可以是可口可乐（coke）或百事可乐（pepsi），它们是装在瓶子中。
 * 我们将创建一个表示食物条目（比如汉堡和冷饮）的 Item 接口和实现 Item 接口的实体类，以及一个表示食物包装的 Packing 接口和实现 Packing 接口的实体类，汉堡是包在纸盒中，冷饮是装在瓶子中。
 * 然后我们创建一个 Meal 类，带有 Item 的 ArrayList 和一个通过结合 Item 来创建不同类型的 Meal 对象的 MealBuilder。BuilderPatternDemo，我们的演示类使用 MealBuilder 来创建一个 Meal
 */

# 食物条目和食物包装的接口
interface Item
{
    public function name();
    public function packing(): IPacking;
    public function price();
}

# 包装接口
interface IPacking
{
    public function pack();
}

# 纸盒包装类
class Wrapper implements IPacking
{
    public function pack()
    {
        return '纸盒包装';
    }
}

# 瓶子包装类
class Bottle implements IPacking
{
    public function pack()
    {
        return '瓶子';
    }
}

# item 接口汉堡抽象类，提供默认功能
abstract class Burger implements Item
{
    public function packing(): IPacking
    {
        return new Wrapper();
    }

    abstract public function price();
}
# item接口冷饮抽象类
abstract class ColdDrink implements Item
{
    public function packing(): IPacking
    {
        return new Bottle();
    }

    abstract public function price();
}

# 扩展 Burger 和 ColdDrink 的实体类
class VegBurger extends Burger
{
    public function price()
    {
        return 25.0;
    }
    public function name()
    {
        return 'Veg Burger,素菜汉堡';
    }
}

class ChickenBurger extends Burger
{
    public function price()
    {
        return 50.5;
    }
    public function name()
    {
        return 'Chicken Burger,鸡肉汉堡';
    }
}

class Pepsi extends ColdDrink
{
    public function price()
    {
        return 35.5;
    }
    public function name()
    {
        return 'Pepsi 百事可乐';
    }
}

class Coke extends ColdDrink
{
    public function price()
    {
        return 35.6;
    }
    public function name()
    {
        return 'Coke 可口可乐';
    }
}

# 5.Meal类，带上item对象
class  Meal
{
    private $items = [];

    # 添加套餐食物条目
    public function addItem(Item $item)
    {
        array_push($this->items, $item);
    }
    # 套餐总价格
    public function getCost()
    {
        $cost = 0;
        foreach ($this->items as $item)
        {
            $cost += $item->price();
        }
        return $cost;
    }

    public function showItems()
    {
        echo "套餐包含：" .PHP_EOL;
        foreach ($this->items as $item) {
            echo "食物名称：". $item->name();
            echo ",包装是:" . $item->packing()->pack();
            echo ",价格是:" .$item->price() . PHP_EOL;
        }
    }
}

# 6 创建一个 MealBuilder 类，实际的 builder 类负责创建 Meal 对象。
class MealBuilder
{
    # 蔬菜套餐：蔬菜汉堡+可口可乐
    public function vegCoke()
    {
        $meal = new Meal();
        $meal->addItem(new VegBurger());
        $meal->addItem(new Coke());
        return $meal;
    }

    # 鸡肉套餐：鸡肉汉堡+百事可乐
    public function ChickenPepsi()
    {
        $meal = new Meal();
        $meal->addItem(new ChickenBurger());
        $meal->addItem(new Pepsi());
        return $meal;
    }
}


class Demo
{
    public function run()
    {
        $mealBuilder = new MealBuilder();
        $vegCoke = $mealBuilder->vegCoke();
        $chickenPepsi = $mealBuilder->ChickenPepsi();

        $vegCoke->showItems();
        echo "蔬菜套餐总价格是:".$vegCoke->getCost() . PHP_EOL;

        $chickenPepsi->showItems();
        echo "鸡肉套餐总价格是:".$chickenPepsi->getCost() . PHP_EOL;
    }
}

$demo = new Demo();
$demo->run();


