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

    // ��ҳ
    public function index(){
        $data = $this->request->param();
        hook('switch_theme',$data);
        $head_controller = new HeadController();
        $head_controller->setHeaderActive("");
        return $this->fetch(':user_agreement');
    }

}