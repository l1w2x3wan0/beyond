<?php
/**
 * User: lwx 2017/08/25 17:37
 * Explain: 门面模式|外观模式
 *
 *
 * 意图：为子系统中的一组接口提供一个一致的界面，外观模式定义了一个高层接口，这个接口使得这一子系统更加容易使用。
 * 主要解决：降低访问复杂系统的内部子系统时的复杂度，简化客户端与之的接口。
 * 何时使用： 1、客户端不需要知道系统内部的复杂联系，整个系统只需提供一个"接待员"即可。 2、定义系统的入口。
 * 如何解决：客户端不与系统耦合，外观类与系统耦合。
 * 关键代码：在客户端和复杂系统之间再加一层，这一层将调用顺序、依赖关系等处理好。
 * 优点： 1、减少系统相互依赖。 2、提高灵活性。 3、提高了安全性。
 * 缺点：不符合开闭原则，如果要改东西很麻烦，继承重写都不合适。
 * 使用场景： 1、为复杂的模块或子系统提供外界访问的模块。 2、子系统相对独立。 3、预防低水平人员带来的风险。
 *
 *
 */
# 1接口
interface IShape
{
    public function draw();
}

# 2.接口实现类
class Square implements IShape
{
    public function draw()
    {
        echo 'Square::draw()' . PHP_EOL;
    }
}

class Rectangle implements IShape
{
    public function draw()
    {
        echo 'Rectangle::draw()' . PHP_EOL;
    }
}


# 3.门面类
class ShapeMake
{
    /**
     * 内部隐藏，门面负责接待
     */

    private $square;
    private $rectangle;

    public function __construct()
    {
        $this->square = new Square();
        $this->rectangle = new Rectangle();
    }

    public function drawSquare()
    {
        $this->square->draw();
    }

    public function drawRectangle()
    {
        $this->rectangle->draw();
    }
}

class Demo
{
    public function run()
    {
        $shapeMake = new ShapeMake();

        $shapeMake->drawSquare();
        $shapeMake->drawRectangle();
    }
}

(new Demo())->run();