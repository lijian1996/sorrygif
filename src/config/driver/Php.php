<?php
namespace Sorry\config\driver;


class Php implements Driver {
    public function parse($config)
    {
        if (is_file($config)) {
            return include $config;
        } else {
            return $config;
        }
    }
}