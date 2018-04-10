<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/26
 * Time: 11:11
 */

namespace App\common\controller;
use App\common\service\Template;
use Sorry\config\Config;
use Sorry\controller\Controller;
use Sorry\lib\Request;

class Frontend extends Controller
{
    protected $jsname='';
    protected $title='';
    protected $code = null;
    protected $data = null;
    protected $msg=null;
  public function _initialize()
  {
      $request = $this->request;
      $config = [
          'site'           =>Config::get('config.site'),
          'jsname'         => $request->module().'/' .($this->jsname?$this->jsname:$request->controller()),
          'module'     => $request->module(),
          'controller' => $request->controller(),
          'action'     => $request->action(),
          'moduleurl'      => "/{$request->module()}",
          'baseUrl'=>PUBLIC_PATH.'/assets/js/'

      ];
      $config['site']['version'] = Config::get('config.debug')?time():$config['site']['version'] ;
      $this->view->assign('templateName',$this->request->param('template'));

      $this->view->assign('menu',Template::instance()->get());
      $this->view->assign('template',$this->request->param('template',Config::get('templateConfig.defaultTemplate')));
      $this->view->assign('config',$config);
      $this->setTitle($this->title);

  }

  protected function setTitle(string $title){
      return $this->view->assign('title',$title);
  }
    public function __destruct()
    {
        //判断是否设置code值,如果有则变动response对象的正文

        if (!is_null($this->code))
        {
            $this->ajaxReturn($this->code,$this->data , $this->msg);
        }
    }


}