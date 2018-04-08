<?php

namespace Sorry\controller;

use Sorry\config\Config;
use Sorry\lib\Request;
use Sorry\view\View;

class Controller
{
    protected $view;
    protected $layout = 'default';
     protected $request;

    public function __construct()
    {
        $this->view = $this->createView();
        $this->request =  Request::instance();
        $this->_initialize();
    }

    public function createView()
    {
        $view = new View(Config::get('view.config'));
        $bases = Config::get('view.base');
        if ($bases) {
            foreach ($bases as $name => $base) {
                $view->assign($name, $base);
            }
        }
        $system['config'] =  Config::get('config');
        $view->assign("System",$system);

        if ($this->layout) {
            $view->layout('layout'.DS . $this->layout);
        }
        return $view;
    }

    protected function _initialize()
    {
    }

    protected function ajaxReturn( $code=1, $data=[], $msg = ''){
        header('Content-Type:application/json; charset=utf-8');
        exit(json_encode(['code'=>$code,'data'=>$data,'time'=>time(),'msg'=>$msg]));
    }


}