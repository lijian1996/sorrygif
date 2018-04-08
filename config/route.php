<?php
return [
    'route' => [

    ],
    'group' => [
          ''=>function($route){
              $route->addRoute(['GET', 'POST'], '/index/[{template}]', 'index.index');
              $route->addRoute(['GET', 'POST'], '/[{template}]', 'index.index');
          }
    ],

];