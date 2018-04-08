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
use Sorry\lib\request\Filter;
use Sorry\lib\request\Server;

class Request
{

    use Server, Filter;

    protected static $instance;

    protected $module;
    protected $controller;
    protected $action;


    protected $param = [];
    protected $get = [];
    protected $post = [];
    protected $request = [];
    protected $put;


    protected function __construct(array $attrs = [])
    {
        foreach ($attrs as $name => $attr) {
            if (property_exists($this, $name)) {
                $this->$name = $attr;
            }
        }
        $this->filter(Config::get('config.default_filter'));


    }


    public static function instance(array $attrs = []): Request
    {
        if (is_null(self::$instance)) {
            self::$instance = new static($attrs);
        }

        return self::$instance;
    }


    public function module(string $module = null)
    {
        if (!is_null($module)) {
            $this->module = $module;
            return $this;
        } else {
            return $this->module ?: '';
        }
    }


    public function controller(string $controller = null)
    {

        if (!is_null($controller)) {
            $this->controller = $controller;
            return $this;
        } else {
            return $this->controller ?: '';
        }
    }


    public function action(string $action = null)
    {
        if (!is_null($action)) {
            $this->action = $action;
            return $this;
        } else {
            return $this->action ?: '';
        }
    }


    public function param($name = '', $default = null, $filter = '')
    {
        if (empty($this->param)) {
            $method = $this->method();
            switch ($method) {
                case 'POST':
                    $vars = $this->post(false);
                    break;
                case 'PUT':
                case 'DELETE':
                case 'PATCH':
                    $vars = $this->put(false);
                    break;
                default:
                    $vars = [];
            }
            $this->param = array_merge($this->get(false), $vars);
        }

        return $this->input($this->param, $name, $default, $filter);
    }



    public function get($name = '', $default = null, $filter = '')
    {
        if (empty($this->get)) {
            $this->get = $_GET;
        }
        return $this->input($this->get, $name, $default, $filter);
    }


    public function post($name = '', $default = null, $filter = '')
    {
        if (empty($this->post)) {
            if (empty($_POST) && $this->contentType() == 'json') {
                $content = $this->getContent();
                $this->post = (array)json_decode($content, true);
            } else {
                $this->post = $_POST;
            }
        }
        return $this->input($this->post, $name, $default, $filter);
    }


    public function put($name = '', $default = null, $filter = '')
    {
        if (is_null($this->put)) {
            $content = $this->getInput();
            if ($this->contentType() == 'json') {
                $this->put = json_decode($content, true);
            } else {
                parse_str($content, $this->put);
            }
        }

        return $this->input($this->put, $name, $default, $filter);
    }


    public function delete($name = '', $default = null, $filter = '')
    {
        return $this->put($name, $default, $filter);
    }


    public function patch($name = '', $default = null, $filter = '')
    {
        return $this->put($name, $default, $filter);
    }


    public function request($name = '', $default = null, $filter = '')
    {
        if (empty($this->request)) {
            $this->request = $_REQUEST;
        }
        return $this->input($this->request, $name, $default, $filter);
    }


}
