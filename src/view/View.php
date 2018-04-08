<?php

namespace Sorry\view;

use Sorry\lib\Request;

/**
 * Class Twig
 */
class View
{
    protected $view;

    public $data;

    public $template;

    public $layout = [];

    protected $config = array(
        'cache' => false,
        'debug' => true,
        'auto_reload' => true,
        'extension' => '.twig',
        'layout_item' => '__CONTENT__'
    );
    protected $path;

    public function __construct(array $config = [])
    {
        if ($config) {
            $this->config = array_merge($this->config, $config);
        }
        $request = Request::instance();
        $this->path = APP_PATH  . $request->module() . VIEW_PATH;
        $loader = new \Twig_Loader_Filesystem($this->path);
        $this->view = new \Twig_Environment($loader, $this->config);
        $this->_initialize();
    }

    protected function _initialize()
    {
        $this->view->getExtension('Twig_Extension_Core')->setEscaper('json', function ($twigEnv, $string, $charset) {
            return addcslashes($string, '');
        });
    }

    public function handle()
    {
        return $this->view;
    }

    public function getPath()
    {
        return $this->path;
    }

    public function assign(string $name, $data)
    {
        $this->data[$name] = $data;
        return true;
    }

    public function display(string $template, array $data = [])
    {
        $template = $this->parseTemplateName($template);
        if ($data) {
            $this->data = array_merge($this->data, $data);
        }
        if (!$this->layout) {
            return $this->view->display($template, $this->data);

        } else {
            $this->data[$this->config['layout_item']] = $template;

            return $this->view->display($this->layout, $this->data);
        }


    }

    public function fetch(string $template, array $data = [])
    {
        $template = $this->parseTemplateName($template);
        if ($data) {
            $this->data = array_merge($this->data, $data);
        }

        return $this->view->render($template, $data);
    }

    protected function parseTemplateName(string $template)
    {
        $len = strlen($this->config['extension']);
        if (substr($template, -$len) != $this->config['extension']) {
            $template .= $this->config['extension'];
        }
        return $template;
    }

    public function layout(string $template)
    {
        $this->layout = $this->parseTemplateName($template);
        return true;
    }


}