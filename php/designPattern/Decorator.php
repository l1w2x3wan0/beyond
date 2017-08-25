<?php
/**
 * User: lwx 2017/08/25 16:32
 * Explain: 装饰器模式
 *
 * 1.装饰器模式（Decorator），可以动态地添加修改类的功能
 * 2.一个类提供了一项功能，如果要在修改并添加额外的功能，传统的编程模式，需要写一个子类继承它，并重新实现类的方法
 * 3.使用装饰器模式，仅需在运行时添加一个装饰器对象即可实现，可以实现最大的灵活性
 *
 * 示例：Web服务层 —— 为 REST 服务提供 JSON 和 XML 装饰器。
 */

# 渲染接口
interface IRenderer
{
    public function renderData();
}

class Webservice implements IRenderer
{
    /**
     * @var mixed
     */
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * @return mixed
     */
    public function renderData()
    {
        return $this->data;
    }
}

# 装饰器抽象类
abstract class Decorator implements IRenderer
{
    /**
     * 装饰器必须实现IRenderer接口，这是装饰器模式的主要特点，否则，就只是一个包裹类
     */

    /**
     * @var IRenderer
     */
    protected $wrapped;

    public function __construct(IRenderer $wrapped)
    {
        $this->wrapped = $wrapped;
    }
}

# 装饰器实现类 xml
class RenderXml extends Decorator
{
    public function renderData()
    {
        $out = $this->wrapped->renderData();

        $doc = new DOMDocument();

        foreach ($out as $key => $value)
        {
            $doc->appendChild($doc->createElement($key, $value));
        }
        return $doc->saveXML();
    }
}
# 装饰器实现类 JSON
class RenderJson extends Decorator
{
    public function renderData()
    {
        $out = $this->wrapped->renderData();
        return json_encode($out, JSON_UNESCAPED_UNICODE);
    }
}

class Demo
{
    public function run()
    {
        $data = ['foo'=> 'bar', 'good'=>'hello'];

        $service = new Webservice($data);

        $renderXml = new RenderXml($service);
        $renderJson = new RenderJson($service);

        $xml = $renderXml->renderData();
        $json = $renderJson->renderData();

        print_r($xml);
        print_r($json);
    }
}

(new Demo())->run();



