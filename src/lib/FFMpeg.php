<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

namespace Sorry\lib;


use Sorry\config\Config;


class FFMpeg
{



    protected static $instance;



    protected $config = [];

    protected $logger=null;

    protected $probe=null;

    protected $FFMpeg=null;

    protected function __construct(array $attrs = [])
    {
        foreach ($attrs as $name => $attr) {
            if (property_exists($this, $name)) {
                $this->$name = $attr;
            }
        }
        if(!$this->config){
            $this->config=Config::get('ffmpeg.config');
        }
      $this->FFMpeg =   \FFMpeg\FFMpeg::create($this->config,$this->logger,$this->probe);
    }

    public function getFFMpeg(){
        return $this->FFMpeg;
    }


    public static function instance(array $attrs = [])
    {
        if (is_null(self::$instance)) {
            self::$instance = new static($attrs);
        }

        return self::$instance;
    }





}
