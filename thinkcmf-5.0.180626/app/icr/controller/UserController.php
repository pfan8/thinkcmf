<?php

/**
 * Created by PhpStorm.
 * User: pfan8
 * Date: 2018/7/30
 * Time: 22:40
 */
namespace app\icr\controller;

use cmf\controller\HomeBaseController;

class UserController extends HomebaseController{

    // 首页
    public function index(){
        $data = $this->request->param();
        hook('switch_theme',$data);
        $head_controller = new HeadController();
        $head_controller->setHeaderActive('home');
        return $this->fetch(':home');
    }
    //提交预约
    public function register($data){
       $this->result($this->request->param(),0,"success",'json');
    }

}