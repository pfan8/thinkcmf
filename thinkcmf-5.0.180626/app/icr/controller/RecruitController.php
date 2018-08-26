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

class RecruitController extends HomebaseController{

    // 首页
    public function index(){
        $data = $this->request->param();
        hook('switch_theme',$data);
        $head_controller = new HeadController();
        $head_controller->setHeaderActive("recruit");
        $recruit_model = new RecruitModel();
        $recruit = $recruit_model->getRecruitList();
        $this->complementRecruit($recruit);
        $brief_intro = $this->getBriefIntro($recruit);
        $this->assign('recruit', $recruit);
        $this->assign('brief_intro', $brief_intro);
        return $this->fetch(':recruit');
    }

    /**
     * 添加招聘
     * @param $data
     * @return
     */
    public function insertRecruit()
    {
        $data = [
            'position' => $_GET['position'],
            'desc' => $_GET['desc'],
            'require' => $_GET['require'],
            'start_time' => $_GET['start_time'],
            'end_time' => $_GET['end_time'],
        ];

        $recruit_model = new RecruitModel();
        $recruit_model->insertRecruit($data);
    }

    /**
     * 修改招聘
     * @param $data
     * @return
     */
    public function updateRecruit()
    {
        $data = [
            'id' => $_GET['id'],
            'position' => $_GET['position'],
            'desc' => $_GET['desc'],
            'require' => $_GET['require'],
            'start_time' => $_GET['start_time'],
            'end_time' => $_GET['end_time'],
        ];

        $recruit_model = new RecruitModel();
        $recruit_model->updateRecruit($data);
    }

    /**
     * 删除招聘
     * @param $data
     * @return
     */
    public function deleteRecruit()
    {
        $recruit_model = new RecruitModel();
        $recruit_model->deleterecruit($_GET['id']);
    }

    /**
     * 通过id查询招聘
     * @param $data
     * @return
     */
    public function getRecruitByID()
    {
        $recruit_model = new RecruitModel();
        return $recruit_model->getRecruitByID($_GET['id']);
    }

    /**
     * 通过职位查询招聘
     * @param $data
     * @return
     */
    public function getRecruitByPosition()
    {
        $recruit_model = new RecruitModel();
        return $recruit_model->getRecruitByPosition($_GET['position']);
    }

    /**
     * 通过描述查询招聘
     * @param $data
     * @return
     */
    public function getRecruitByDesc()
    {
        $recruit_model = new RecruitModel();
        return $recruit_model->getRecruitByDesc($_GET['desc']);
    }

    /**
     * 通过要求查询招聘
     * @param $data
     * @return
     */
    public function getRecruitByRequire()
    {
        $recruit_model = new RecruitModel();
        return $recruit_model->getRecruitByRequire($_GET['require']);
    }

    /**
     * 通过开始时间查询招聘
     * @param $data
     * @return
     */
    public function getRecruitByStartTime()
    {
        $recruit_model = new RecruitModel();
        return $recruit_model->getRecruitByStartTime($_GET['start_time']);
    }

    /**
     * 通过截止时间查询招聘
     * @param $data
     * @return
     */
    public function getRecruitByEndTime()
    {
        $recruit_model = new RecruitModel();
        return $recruit_model->getRecruitByEndTime($_GET['end_time']);
    }

    /**
     * 查询指定开始时间之后的招聘
     * @param $data
     * @return
     */
    public function getRecruitAfterStartTime()
    {
        $recruit_model = new RecruitModel();
        return $recruit_model->getRecruitAfterStartTime($_GET['start_time']);
    }

    /**
     * 查询指定截止时间之前的招聘
     * @param $data
     * @return
     */
    public function getRecruitBeforeEndTime()
    {
        $recruit_model = new RecruitModel();
        return $recruit_model->getRecruitBeforeEndTime($_GET['end_time']);
    }

    /**
     * 转换简介到html输出
     * @param $recruit
     * @return
     */
    private function getBriefIntro($recruit)
    {
        $brief_intro = [];
        foreach ($recruit as $item) {
            $intro_list = explode("\n",$item['desc']);
            $bi_item = "";
            for ($x = 0; $x < count($intro_list)-1; $x++) {
                $bi_item .= "<p>" . $intro_list[$x] . "</p>\n";
            }
            $bi_item .= "<p>" . $intro_list[count($intro_list)-1] . "</p>\n";
            $brief_intro[] = $bi_item;
        }
        return $brief_intro;
    }

    private function complementRecruit(&$recruits)
    {
        for ($i = 0; $i < count($recruits); $i++) {
            $recruit = $recruits->shift();
            if (empty($recruit['icon'])) {
                $recruit['icon'] = '/themes/RY/icr/imgs/timg.jpg';
            }
            $recruits->push($recruit);
        }
        while (count($recruits) < 3) {
            $recruit = [
                'id' => 0,
                'icon' => '/themes/RY/icr/imgs/timg.jpg',
                'position' => '待添加',
                'desc' => '待添加',
                'require' => '待添加',
            ];
            $recruits->push($recruit);
        }
    }
}