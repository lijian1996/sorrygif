<?php

namespace Sorry\route;

use Sorry\App;
use Sorry\config\Config;
use Sorry\lib\Request;


class Route
{

    public static function run()
    {
        $dispatcher = \FastRoute\simpleDispatcher(function (\FastRoute\RouteCollector $r) {
            $routeConfig = Config::get('route');

            if (isset($routeConfig['route'])) {
                foreach ($routeConfig['route'] as $route) {
                    $r->addRoute($route[0], $route[1], $route[2]);
                }
            }
            if (isset($routeConfig['group'])) {
                foreach ($routeConfig['group']  as $key=> $group) {

                    $r->addGroup($key, $group);
                }
            }

        });

// Fetch method and URI from somewhere

        $httpMethod = $_SERVER['REQUEST_METHOD'];
        $uri = $_SERVER['REQUEST_URI'];

// Strip query string (?foo=bar) and decode URI
        if (false !== $pos = strpos($uri, '?')) {
            $uri = substr($uri, 0, $pos);
        }
        $uri = rawurldecode($uri);
        $routeInfo = $dispatcher->dispatch($httpMethod, $uri);
        self::parseRouteInfo($routeInfo);
    }

    private static function parseRouteInfo(array $info)
    {

        switch ($info[0]) {
            case \FastRoute\Dispatcher::NOT_FOUND:
                // todo 404页面
                break;
            case \FastRoute\Dispatcher::FOUND:
                $handler = explode('.', $info[1], 3);
                $vars = $info[2];
                App::run(self::parseHandler($handler), $vars);
                // ... call $handler with $vars
                break;
        }
    }


    private static function parseHandler($handler)
    {
        $request = Request::instance();
        $request->action(array_pop($handler));
        $request->controller($handler ? array_pop($handler) : Config::get('config.default_controller'));
        $request->module($handler ? array_pop($handler) : Config::get('config.default_module'));

        $result = [   $request->module() ,   $request->controller(),    $request->action()];
        return $result;
    }

}