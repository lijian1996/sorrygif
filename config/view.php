<?php
return [
    'config'=>[  //twig 配置
        'cache' => TEMP_PATH.'view'.DS,
        'debug' => \Sorry\config\Config::get('config.debug'),
        'auto_reload' => true,
        'extension' => '.twig'
    ],
    'base'=>[  //模板自带遍历
        "__PUBLIC__"=>PUBLIC_PATH
    ]


];