<?php

/**
 * Created by PhpStorm.
 * User: pfan8
 * Date: 2018/7/30
 * Time: 22:40
 */
namespace app\icr\controller;

use cmf\controller\HomeBaseController;

class TeacherController extends HomebaseController{

    // 首页
    public function index(){
        $head_controller = new HeadController();
        $head_controller->setHeaderActive("teacher");
        return $this->fetch(':teachers');
    }

}