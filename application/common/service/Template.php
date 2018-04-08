<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/26
 * Time: 11:11
 */

namespace App\common\service;

use Sorry\config\Config;
use Sorry\lib\FFMpeg;
use Sorry\lib\ffmpeg\filters\gif\GifSubtitlesFilter;
use Sorry\lib\Request;
use Sorry\view\View;

class Template
{
    protected $config;
    protected $templates;
    protected static $instance;

    protected function __construct()
    {
        $this->config = Config::get('templateConfig');
        $this->templates = Config::get('template');
    }

    public static function instance(): Template
    {
        if (is_null(self::$instance)) {
            self::$instance = new static();
        }
        return self::$instance;
    }

    public function has(string $template): bool
    {
        return key_exists($template, $this->templates);
    }

    public function get(string $template = null)
    {
        if ($template === null) {
            return $this->templates;
        } elseif ($this->has($template)) {
            return $this->templates[$template];
        } else {
            return false;
        }
    }

    public function getConfig(string $name = null)
    {
        if ($name !== null) {
            return key_exists($name, $this->config) ? $this->config[$name] : $this->config;
        } else {
            return $this->config;
        }
    }

    public function addGif(string $template, array $subtitles = [], $spec = 'sm')
    {
        $data = $this->get($template);
        if (!$data) {
            return false;
        }

        array_walk($data['subtitles'], function (&$value, $key, $subtitles) {
            if (isset($subtitles[$key]) && $subtitles[$key]) {
                $value = $subtitles[$key];
            }
        }, $subtitles);
        $id = $this->createId($template,$spec. implode(',', $data['subtitles']));
        $data['spec']=$spec;
        return $this->createGif($template, $id, $data);

    }

    public function createGif($template, $id, $option)
    {

        $gifFileName = $this->createFileName($template, $id);
        if ($this->findGif('.' .$gifFileName)) {
            return $gifFileName;
        }
        $gifDir = '.' .dirname($gifFileName);


        if (!is_dir($gifDir)) {
            mkdir($gifDir, 0777, true);
        }

        $subFileName = $this->createFileName($template, $id, 'ass');
        $this->createTempSub('.' . $option['subtitleDir'],  '.'.$subFileName, $option['subtitles']);

        $ffmpeg = FFMpeg::instance()->getFFMpeg();
        $video = $ffmpeg->open('.' .$this->getVideo($option));
        $gif = $video->gif(\FFMpeg\Coordinate\TimeCode::fromSeconds(0), new \FFMpeg\Coordinate\Dimension($this->config['width'], $this->config['height']));
        $gif->addFilter(new GifSubtitlesFilter('.'.$subFileName))
            ->save('.'.$gifFileName);

        unlink('.'.$subFileName);//删除临时生成的字幕文件
        return $gifFileName;
    }

    protected function getVideo(array $option){
          if(isset($option['spec']) && $option['spec']=='sm'){
                return $option['videoSmDir'];
        }
        return   $option['videoDir'];
    }

    protected function createTempSub($subtitleDir, $tempDir, array $subtitles = []): string
    {
        foreach ($subtitles as $key => $value) {
            $reSub[$key] = '<%= sentences[' . $key . '] %>';

        }
        $subtitles = str_replace($reSub, $subtitles, file_get_contents($subtitleDir));
        $subFile = fopen($tempDir, "w");
        fwrite($subFile, $subtitles);
        fclose($subFile);
        return true;
    }

    public function findGif(string $file)
    {
        return file_exists($file);
    }

    protected function createFileName(string $template, $id, $type = 'gif'): string
    {
        return $this->config['cacheDir'] . '/' . $template . '/' . $id . '.' . $type;
    }


    protected function createId(string $template, string $param): string
    {

        return md5(base64_encode($template . $param));
    }


}