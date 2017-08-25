<?php
/**
 * User: lwx 2017/08/24 15:09
 * Explain: 桥接模式
 */

interface IDrawAPI
{
    public function drawCircle(int $radius, int $x, int $y);
}

class RedCircle implements IDrawAPI
{
    public function drawCircle(int $radius, int $x, int $y)
    {
        echo "Drawing Circle[ color: red, radius: "
            . $radius . ", x: " . $x .", ". $y ."]" . PHP_EOL;
    }
}

class GreenCircle implements IDrawAPI
{
    public function drawCircle(int $radius, int $x, int $y)
    {
        echo "Drawing Circle[ color: green, radius: "
            . $radius . ", x: " . $x .", ". $y ."]" . PHP_EOL;
    }
}

# 3.使用 DrawAPI 接口创建抽象类 Shape。
abstract class Shape
{
    protected $drawAPI;

    protected function __construct(IDrawAPI $drawAPI)
    {
        $this->drawAPI = $drawAPI;
    }

    public abstract function draw();
}

# 4.实现shape接口的实体类
class Circle extends Shape
{
    private $x, $y, $radius;

    public function __construct(int $x, int $y, int $radius, IDrawAPI $drawAPI)
    {
        parent::__construct($drawAPI);

        $this->x = $x;
        $this->y = $y;
        $this->radius = $radius;
    }

    public function draw()
    {
        $this->drawAPI->drawCircle($this->radius, $this->x, $this->y);
    }
}

# 5.使用 Shape 和 DrawAPI 类画出不同颜色的圆。
class Demo
{
    public function run()
    {
        $redCircle = new Circle(100, 100, 10, new RedCircle());
        $greenCircle = new Circle(100, 100, 10, new GreenCircle());

        $redCircle->draw();
        $greenCircle->draw();
    }
}

(new Demo())->run();
