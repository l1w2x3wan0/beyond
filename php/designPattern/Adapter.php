<?php
/**
 * User: lwx 2017/08/24 14:15
 * Explain: 适配器模式
 *
 * 意图：将一个类的接口转换成客户希望的另外一个接口。适配器模式使得原本由于接口不兼容而不能一起工作的那些类可以一起工作。
 * 主要解决：主要解决在软件系统中，常常要将一些"现存的对象"放到新的环境中，而新环境要求的接口是现对象不能满足的。
 * 何时使用： 1、系统需要使用现有的类，而此类的接口不符合系统的需要。
 *          2、想要建立一个可以重复使用的类，用于与一些彼此之间没有太大关联的一些类，包括一些可能在将来引进的类一起工作，这些源类不一定有一致的接口。
 *          3、通过接口转换，将一个类插入另一个类系中。（比如老虎和飞禽，现在多了一个飞虎，在不增加实体的需求下，增加一个适配器，在里面包容一个虎对象，实现飞的接口。）
 * 如何解决：继承或依赖（推荐）。
 * 关键代码：适配器继承或依赖已有的对象，实现想要的目标接口
 * 优点： 1、可以让任何两个没有关联的类一起运行。 2、提高了类的复用。 3、增加了类的透明度。 4、灵活性好。
 * 缺点： 1、过多地使用适配器，会让系统非常零乱，不易整体进行把握
 * 使用场景：有动机地修改一个正常运行的系统的接口，这时应该考虑使用适配器模式
 */

# 1.为媒体播放器和更高级的媒体播放器创建接口。
interface IMediaPlayer
{
    public function play(string $audioType, string $fileName);
}

interface IAdvanceMediaPlayer
{
    public function playVlc(string $fileName);
    public function playMp4(string $filename);
}

# 2.IAdvanceMediaPlayer接口实体类
class VlcPlayer implements IAdvanceMediaPlayer
{
    public function playVlc(string $fileName)
    {
        echo 'Playing vlc file. Name:' . $fileName .PHP_EOL;
    }
    public function playMp4(string $filename)
    {
        // TODO: Implement playMp4() method.
    }
}

class Mp4Player implements IAdvanceMediaPlayer
{
    public function playVlc(string $fileName)
    {

    }
    public function playMp4(string $filename)
    {
        echo 'Playing mp4 file. Name:' . $filename .PHP_EOL;
    }
}

# 3.实现IMediaPlayer 接口适配器类
class MediaAdapter implements IMediaPlayer
{
    protected $advanceMusicPlayer;

    public function __construct(string $audioType)
    {
        if ('vlc' == $audioType){
            $this->advanceMusicPlayer = new VlcPlayer();
        }elseif ('mp4' == $audioType) {
            $this->advanceMusicPlayer = new Mp4Player();
        }
    }

    public function play(string $audioType, string $fileName)
    {
        if ('vlc' == $audioType) {
            $this->advanceMusicPlayer->playVlc($fileName);
        }elseif ('mp4' == $audioType) {
            $this->advanceMusicPlayer->playMp4($fileName);
        }
    }
}

# 4 IMediaPlayer接口的实体类
class AudioPlayer implements IMediaPlayer
{
    protected $mediaAdapter;

    public function play(string $audioType, string $fileName)
    {
        if ('mp3' == $audioType){
            echo 'Playing mp3 file. Name: ' . $fileName . PHP_EOL;
        } elseif ( in_array($audioType, ['mp4', 'vlc'])) {
            $this->mediaAdapter = new MediaAdapter($audioType);
            $this->mediaAdapter->play($audioType, $fileName);
        } else {
            echo 'Invalid media. ' . $audioType . ' format not supported' .PHP_EOL;
        }
    }
}

# 5 使用AudioPlay来播放不同类型的音频格式
class Demo
{
    public function run()
    {
        $audioPlay = new AudioPlayer();

        $audioPlay->play("mp3", "beyond the horizon.mp3");
        $audioPlay->play("mp4", "alone.mp4");
        $audioPlay->play("vlc", "far far away.vlc");
        $audioPlay->play("avi", "mind me.avi");
    }
}

(new Demo())->run();
