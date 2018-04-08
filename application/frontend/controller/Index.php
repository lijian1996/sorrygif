<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/26
 * Time: 11:11
 */

namespace App\frontend\controller;

use App\common\controller\Frontend;
use App\common\service\Template;
use Sorry\config\Config;

class Index extends Frontend
{

    public function index($template = '')
    {
        $defaultTemplate = Config::get('templateConfig.defaultTemplate');
        $template=$template?strtolower($template):$defaultTemplate;
        $templateService = Template::instance();
        if (!$this->request->isAjax()) {
            if (!$data = $templateService->get($template)) {
                $data= $templateService->get($defaultTemplate);
            }
            $this->setTitle('在线生成'.$data['name'].'动图');
            $this->view->assign('template',$template);
            $this->view->assign('data', $data);
            $this->view->display('index');
        } else {
            $this->code=0;
            $subtitle = $this->request->param('subtitle/array');
            $small = $this->request->param('small/int',0);
            if(!$gif=$templateService->addGif($template,$subtitle,$small?'sm':'md')){
                $this->msg='生成失败';
            }else{
                $this->code=1;
                $this->data['gif'] = $gif;

            }
            return ;

        }


    }


}