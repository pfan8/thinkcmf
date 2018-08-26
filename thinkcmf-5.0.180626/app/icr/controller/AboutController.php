<?php

/**
 * Created by PhpStorm.
 * User: pfan8
 * Date: 2018/7/30
 * Time: 22:40
 */
namespace app\icr\controller;

use cmf\controller\HomeBaseController;

class AboutController extends HomebaseController{

    // 首页
    public function index(){
        $data = $this->request->param();
        hook('switch_theme',$data);
        $head_controller = new HeadController();
        $head_controller->setHeaderActive("about");
        return $this->fetch(':about');
    }

}