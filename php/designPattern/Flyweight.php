<?php
/**
 * User: lwx 2017/08/25 18:05
 * Explain: 享元模式
 *
 * 主要解决：在有大量对象时，有可能会造成内存溢出，我们把其中共同的部分抽象出来，如果有相同的业务请求，直接返回在内存中已有的对象，避免重新创建。
 * 如何解决：用唯一标识码判断，如果在内存中有，则返回这个唯一标识码所标识的对象。
 *
 */

# 1.接口
interface Shape
{
    public function draw();
}
# 2.实现接口的实体类
class Circle implements Shape
{
    private $color;
    private $x, $y, $radius;

    public function __construct($color)
    {
        $this->color = $color;
    }

    public function setRadius(int $radius)
    {
        $this->radius = $radius;
    }

    public function setX(int $x)
    {
        $this->x = $x;
    }

    public function setY(int $y)
    {
        $this->y = $y;
    }

    public function draw()
    {
        echo 'Circle: Draw() [color:' . $this->color .',x:'.$this->x.',y:'.$this->y . ',radius:'.$this->radius .PHP_EOL;
    }
}

# 3 工厂类，生成给定信息的实体类的对象
class ShapeFactory
{
    private static $shapes;

    public static function getCircle(string $color)
    {
        $circle = null;
        if (isset(self::$shapes[$color]))
        {
            $circle = self::$shapes[$color];
        }else{
            $circle = new Circle($color);
            self::$shapes[$color] = $circle;
            echo 'Creating circle of color : ' . $color . PHP_EOL;
        }
        return $circle;
    }
}

# 4 使用该工厂，通过传递颜色信息，获取实体类的对象
class Demo
{
    const color = [ "Red", "Green", "Blue", "White", "Black" ];

    public function run()
    {
        for ($i = 0; $i < 20; $i++){
            $circle = ShapeFactory::getCircle($this->getRandomColor());
            $circle->setX($this->getRandomX());
            $circle->setY($this->getRandomY());
            $circle->setRadius(100);
            $circle->draw();
        }
    }

    private function getRandomColor()
    {
        return self::color[rand(0, count(self::color)-1)];
    }
    private function getRandomX()
    {
        return rand(0, 100);
    }

    private function getRandomY()
    {
        return rand(0, 100);
    }
}

(new Demo())->run();
(new Demo())->run();
(new Demo())->run();
