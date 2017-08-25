<?php
/**
 * User: lwx 2017/08/24 17:02
 * Explain: 组合模式
 *
 * 军队组合、武力计算
 * Unit总军队是树干（基类）、army分队是树枝（组合对象）、射手和激光炮是树叶（局部对象）
 *
 * 缺陷：
 *   代码冗余： Archer 和 LaserCanonUnit的 addUnit、removeUnit方法
 *   扩展不便： 新增骑兵（叶子）或者新增骑兵团（集合）不会有冗余代码
 *
 */

class UnitException extends Exception {}

abstract class Unit
{
    abstract public function addUnit(Unit $unit);  //加入单位对象
    abstract public function removeUnit(Unit $unit);//删除单位对象
    abstract public function bombardStrength();//计算攻击力
}

# 射手
class Archer extends Unit
{
    public function addUnit(Unit $unit)
    {
        throw new UnitException(get_class($this) .' is a left');
    }
    public function removeUnit(Unit $unit)
    {
        throw new UnitException(get_class($this) .' is a left');
    }
    public function bombardStrength()
    {
        return 4;
    }
}
# 激光炮
class LaserCanonUnit extends Unit
{
    public function addUnit(Unit $unit)
    {
        throw new UnitException(get_class($this) .' is a left');
    }
    public function removeUnit(Unit $unit)
    {
        throw new UnitException(get_class($this) .' is a left');
    }
    public function bombardStrength()
    {
        return 44;
    }
}
# 军队
class Army extends Unit
{
    private $units = [];

    public function addUnit(Unit $unit)
    {
        if (in_array($unit, $this->units, true)){
            return;
        }
        $this->units[] = $unit;
    }
    public function removeUnit(Unit $unit)
    {
        $units = [];
        foreach ($this->units as $thisUnit) {
            if ($unit !== $thisUnit)
                $units[] = $thisUnit;
        }
        $this->units = $units;
    }
    public function bombardStrength()
    {
        $ret = 0;
        foreach ($this->units as $unit) {
            $ret += $unit->bombardStrength();
        }
        return $ret;
    }
}

class Demo
{
    public function run()
    {
        $main_army = new Army();
        $main_army->addUnit(new Archer());          //增加一个射手 +4
        $main_army->addUnit(new LaserCanonUnit());  //增加一个激光炮 +44

        $sub_army = new Army();
        $sub_army->addUnit(new Archer());           //增加一个射手 +4
        $sub_army->addUnit(new Archer());           //增加一个射手 +4
        $sub_army->addUnit($archer = new Archer()); //增加一个射手 +4
        $sub_army->addUnit(new Archer());           //增加一个射手 +4

        $sub_army->removeUnit($archer);
        $main_army->addUnit($sub_army);
        echo '军队的总攻击力为:' . $main_army->bombardStrength() . PHP_EOL;

        $sub_army->addUnit(new Archer());           //子军队 增加一个射手 +4
        echo '军队的总攻击力为:' . $main_army->bombardStrength() . PHP_EOL;
    }
}

(new Demo())->run();


