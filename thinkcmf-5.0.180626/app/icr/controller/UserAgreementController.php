<?php

/**
 * Created by PhpStorm.
 * User: SCC
 * Date: 2018/7/30
 * Time: 22:40
 */
namespace app\icr\controller;

use cmf\controller\HomeBaseController;

class UserAgreementController extends HomebaseController{

    // Ê×Ò³
    public function index(){
        $head_controller = new HeadController();
        $head_controller->setHeaderActive("");
        return $this->fetch(':user_agreement');
    }

}