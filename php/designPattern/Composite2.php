<?php
/**
 * User: lwx 2017/08/25 11:09
 * Explain: 组合模式 改进版
 */

class UnitException extends Exception {}

interface IUnit
{
    public function addUnit(IUnit $unit);    //加入单位对象
    public function removeUnit(IUnit $unit); //删除单位对象
    public function bombardStrength();      //计算攻击力
    public function show();                 //显示各单位
}

abstract class AUnit implements IUnit
{
    protected $name;
    protected $bombard;
    protected $units;

    public function __construct($name, $bombard = 0)
    {
        $this->name     = $name;
        $this->bombard  = $bombard ? $bombard : $this->bombard;
    }

    public function bombardStrength()
    {
        return $this->bombard;
    }

    # 显示
    public function show($deep = 0)
    {
        if (isset($this->units) && $this->units){
            echo str_repeat(' ', $deep) .'|- ' . get_class($this) . ', name: '. $this->name.'. 包含:---' .PHP_EOL;
            foreach ($this->units as $unit)
            {
                $unit->show($deep + 4);
            }
        }else{
            echo str_repeat(' ', $deep) .'|- ' . get_class($this) . ', name: '. $this->name .', 攻击力为: '.$this->bombard .PHP_EOL;
        }
    }

    public function __get($name)
    {
        if (isset($this->$name))
            return $this->$name;
        else
            throw new UnitException(get_class($this) . ' 没有这个属性');
    }

}
# 单个 士兵
abstract class ASingle extends AUnit
{
    public function addUnit(IUnit $unit)
    {
        throw new UnitException(get_class($this) .' is a left');
    }
    public function removeUnit(IUnit $unit)
    {
        throw new UnitException(get_class($this) .' is a left');
    }
}

# 集合 军队
abstract class AGroup extends AUnit
{
    protected $units = [];

    public function addUnit(IUnit $unit)
    {
        if (in_array($unit, $this->units)){
            return;
        }
        $this->units[$unit->name] = $unit;
        $this->bombard += $unit->bombard;
    }

    public function removeUnit(IUnit $unit)
    {
        if (isset($this->units[$unit->name])){
            unset($this->units[$unit->name]);
            $this->bombard -= $unit->bombard;
        }
    }
}

# 射手
class Archer extends ASingle
{
    protected $bombard = 4;
}
# 激光炮
class LaserCanonUnit extends ASingle
{
    protected $bombard = 40;
}

# 军队
class Army extends AGroup
{

}

/**---------------- 以下为新增类别 -------------**/

# 骑兵
class Cavalryman extends ASingle
{
    protected $bombard = 100;
}

# 骑兵团 只能加入骑兵
class CavalrymanGroup extends AGroup
{
    public function addUnit(IUnit $unit)
    {
        if ($unit instanceof Cavalryman){ //是骑兵
            parent::addUnit($unit);
        }else{
            throw new UnitException('骑兵团只能加入骑兵');
        }
    }
}

# 船，不能上骑兵
class Shop extends AGroup
{
    public function addUnit(IUnit $unit)
    {
        if ($unit instanceof Cavalryman || $unit instanceof CavalrymanGroup){
            throw new UnitException('骑兵或者骑兵团不能上船');
        }else{
            parent::addUnit($unit);
        }
    }
}

class Demo
{
    public function run()
    {
        $main_army = new Army('华东军');
        $main_army->addUnit(new Archer('射手_1'));          //增加一个射手 +4
        $main_army->addUnit(new LaserCanonUnit('激光炮_1'));  //增加一个激光炮 +44

        $sub_army = new Army('华东军_第一师');
        $sub_army->addUnit(new Archer('射手_2'));
        $sub_army->addUnit(new Archer('射手_3'));
        $sub_army->addUnit($lihua = new Archer('射手_lihua'));
        $sub_army->addUnit(new Archer('射手_5'));

        $sub_army->removeUnit($lihua);
        $main_army->addUnit($sub_army);

        $CavalrymanGroup = new CavalrymanGroup('骑兵团_1');
        $CavalrymanGroup->addUnit(new Cavalryman('骑兵_1', 200));  #组团，威力加大
        $CavalrymanGroup->addUnit(new Cavalryman('骑兵_2', 200));
        $CavalrymanGroup->addUnit(new Cavalryman('骑兵_3', 200));

        $Shop = new Shop('运输船_1');
        $Shop->addUnit(new Archer('射手_21'));
        $Shop->addUnit(new Archer('射手_22'));
        $Shop->addUnit(new LaserCanonUnit('激光炮_21'));
        $Shop->addUnit(new LaserCanonUnit('激光炮_22'));
        //$Shop->addUnit(new Cavalryman('骑兵_21'));  // 异常，骑兵不能上船

        $main_army->addUnit($Shop);
        $main_army->addUnit($CavalrymanGroup);

        echo '军队的总攻击力为:' . $main_army->bombardStrength() . PHP_EOL;
        $main_army->show();

    }
}

(new Demo())->run();


/**
 * 结果如下：

军队的总攻击力为:744
|- Army, name: 华东军. 包含:---
    |- Archer, name: 射手_1, 攻击力为: 4
    |- LaserCanonUnit, name: 激光炮_1, 攻击力为: 40
    |- Army, name: 华东军_第一师. 包含:---
        |- Archer, name: 射手_2, 攻击力为: 4
        |- Archer, name: 射手_3, 攻击力为: 4
        |- Archer, name: 射手_5, 攻击力为: 4
    |- Shop, name: 运输船_1. 包含:---
        |- Archer, name: 射手_21, 攻击力为: 4
        |- Archer, name: 射手_22, 攻击力为: 4
        |- LaserCanonUnit, name: 激光炮_21, 攻击力为: 40
        |- LaserCanonUnit, name: 激光炮_22, 攻击力为: 40
    |- CavalrymanGroup, name: 骑兵团_1. 包含:---
        |- Cavalryman, name: 骑兵_1, 攻击力为: 200
        |- Cavalryman, name: 骑兵_2, 攻击力为: 200
        |- Cavalryman, name: 骑兵_3, 攻击力为: 200

 */

