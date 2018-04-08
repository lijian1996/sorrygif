<?php

namespace Sorry\config;
class Config
{
    /**
     * @var array 配置参数
     */
    private static $config = [];



    public static function load(string $file, string $name)
    {
        if (is_file($file)) {
            $name = strtolower($name);
            $type = pathinfo($file, PATHINFO_EXTENSION);
            return self::parse($file, $type, $name);
        }
        return self::$config;
    }


    public static function parse(string $config, string $type = null, string $name = '')
    {
   ;

        if ($type==null) $type = pathinfo($config, PATHINFO_EXTENSION);

        $class = false !== strpos($type, '\\') ? $type : '\\Sorry\\config\\driver\\' . ucwords($type);

        return self::set($name,(new $class())->parse($config));
    }

    public static function set($name, $value = null)
    {
        self::$config[$name]=$value;
        return self::$config[$name];
    }


    public static function get(string $name, string $ext = null)
    {
        $ext = $ext ? $ext : CONF_EXT;
        $name = explode('.', $name);
        $name[0] = strtolower($name[0]);
        $file = CONF_PATH . $name[0] . $ext;;
        if (!isset(self::$config[$name[0]])) {
            is_file($file) && self::load($file, $name[0]);
        }

        return self::autoGet($name);
    }

    private static function autoGet(array $names)
    {
        $config = null;
        if (isset(self::$config[$names[0]])) {
            $config = self::$config[$names[0]];
            unset($names[0]);
            $call = function ($name, $config) {
                return isset($config[$name]) ? $config[$name] : null;
            };
            foreach ($names as $name) {
                $config = $call($name, $config);
                if ($config == null) {
                    break;
                }
            }
        }
        return $config;

    }


}
