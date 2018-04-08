<?php

namespace Sorry;
use Sorry\lib\Request;

class App
{
    public static function run(array $method, array $vars = [])
    {


        $class = self::initClass('App\\'.$method[0].'\\controller\\'.ucfirst($method[1]),$vars);
        $reflect = new \ReflectionMethod($class, $method[2]);
        $args = self::bindVar($reflect, $vars);

        return $reflect->invokeArgs(isset($class) ? $class : null, $args);
    }

    public static function initClass(string $class, array $vars = [])
    {
        $reflect = new \ReflectionClass($class);
        $constructor = $reflect->getConstructor();
        $args = $constructor ? self::bindVar($constructor, $vars) : [];

        return $reflect->newInstanceArgs($args);
    }

    /**
     * @param \ReflectionMethod|\ReflectionFunction $reflect
     * @param array $vars
     * @return array
     */
    private static function bindVar(object $reflect, array $vars = [])
    {
        if (empty($vars)) {
            $vars = Request::instance()->param();
        }else{
            $vars = Request::instance()->input($vars);
        }
        $args = [];
        if ($reflect->getNumberOfParameters() > 0) {
            reset($vars);
            $type = key($vars) === 0 ? 1 : 0;
            foreach ($reflect->getParameters() as $param) {
                $args[] = self::getParamValue($param, $vars, $type);
            }
        }

        return $args;
    }

    private static function getParamValue(\ReflectionParameter $param, array &$vars, int $type)
    {
        $name = $param->getName();
        if (1 == $type && !empty($vars)) {
            $result = array_shift($vars);
        } elseif (0 == $type && key_exists($name, $vars)) {
            $result = $vars[$name];
        } elseif ($param->isDefaultValueAvailable()) {
            $result = $param->getDefaultValue();
        } else {
            throw new \InvalidArgumentException('miss method  param :' . $name);
        }

        return $result;
    }

}