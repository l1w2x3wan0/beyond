<?php
/**
 * User: lwx 2017/08/28 11:01
 * Explain: 代理模式
 *
 * 意图：为其他对象提供一种代理以控制对这个对象的访问。
 * 主要解决：在直接访问对象时带来的问题
 * 何时使用：想在访问一个类时做一些控制。
 * 如何解决：增加中间层。
 * 关键代码：实现与被代理类组合。
 * 优点： 1、职责清晰。 2、高扩展性。 3、智能化。
 * 缺点： 1、由于在客户端和真实主题之间增加了代理对象，因此有些类型的代理模式可能会造成请求的处理速度变慢。
 *          2、实现代理模式需要额外的工作，有些代理模式的实现非常复杂。
 * 使用场景：按职责来划分，通常有以下使用场景：
 *      1、远程代理。
 *      2、虚拟代理。
 *      3、Copy-on-Write 代理。
 *      4、保护（Protect or Access）代理。
 *      5、Cache代理。
 *      6、防火墙（Firewall）代理。
 *      7、同步化（Synchronization）代理。
 *      8、智能引用（Smart Reference）代理。
 * 注意事项： 1、和适配器模式的区别：适配器模式主要改变所考虑对象的接口，而代理模式不能改变所代理类的接口。
 *          2、和装饰器模式的区别：装饰器模式为了增强功能，而代理模式是为了加以控制。
 * 实现
 * 我们将创建一个 Image 接口和实现了 Image 接口的实体类。ProxyImage 是一个代理类，减少 RealImage 对象加载的内存占用
 */

# 图片接口
interface Image
{
    public function display();
}

# 图片接口实体类
class RealImage implements Image
{
    private $fileName;

    public function __construct(string $fileName)
    {
        $this->fileName = $fileName;
    }

    public function display()
    {
        echo 'Displaying ' . $this->fileName .PHP_EOL;
    }
}

# 代理
class ProxyImage implements Image
{
    private $realImage;
    private $fileName;

    public function __construct(string $fileName)
    {
        $this->fileName = $fileName;
        $this->loadFromDisk($this->fileName);
    }

    public function display()
    {
        if ($this->realImage == null){
            $this->realImage = new RealImage($this->fileName);
        }
        $this->realImage->display();
    }

    # 从Cache获取
    private function loadFromDisk(string $fileName){
        echo 'Loading ' . $fileName . PHP_EOL;
    }
}

# 3 当请求时，使用ProxyImage来获取RealImage类的对象
class Demo
{
    public function run()
    {
        $ProxyImage = new ProxyImage('test_10mb.jpg');

        $ProxyImage->display();

        echo ''.PHP_EOL;

        $ProxyImage->display();
    }
}

(new Demo())->run();
