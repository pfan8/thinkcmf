<?php

/**
 * Created by PhpStorm.
 * User: pfan8
 * Date: 2018/7/30
 * Time: 22:40
 */
namespace app\icr\controller;

use app\icr\model\RecruitModel;
use cmf\controller\HomeBaseController;

class RecruitdetailController extends HomebaseController{

    // 首页
    public function index(){
        $head_controller = new HeadController();
        $head_controller->setHeaderActive("recruit");
        $recruit_model = new RecruitModel();
        $recruit = $recruit_model->getRecruitByID(1);
        $desc = $this->transformDesc($recruit['desc']);
        $require = $this->transformRequire($recruit['require']);
        $this->assign('recruit', $recruit);
        $this->assign('desc', $desc);
        $this->assign('require', $require);
        return $this->fetch(':recruit-detail');
    }

    private function transformDesc($desc) {
        $desc_html = "";
        $desc_list = explode("\n", $desc);
        foreach ($desc_list as $item) {
            $desc_html .= "<p>" . $item . "</p>\n";
        }
        return $desc_html;
    }

    private function transformRequire($require) {
        $require_html = "";
        $require_list = explode("\n", $require);
        foreach ($require_list as $item) {
            $require_html .= "<p>" . $item . "</p>\n";
        }
        return $require_html;
    }

}