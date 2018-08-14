<?php

/**
 * Created by PhpStorm.
 * User: pfan8
 * Date: 2018/7/30
 * Time: 22:40
 */
namespace app\icr\controller;

use app\icr\model\SchoolModel;
use cmf\controller\HomeBaseController;

class HeadController extends HomebaseController{

    public function _initialize()
    {
        parent::_initialize(); // TODO: Change the autogenerated stub
        $this->assign('home_active', "");
        $this->assign('course_active', "");
        $this->assign('teacher_active', "");
        $this->assign('school_active', "");
        $this->assign('recruit_active', "");
        $this->assign('join_active', "");
        $this->assign('about_active', "");
        $school_model = new SchoolModel();
        $city_list = $school_model->getCityList();
        $this->assign('city_list',$city_list);
        $city = session('city');
        if(!$city){
            $city = $city_list[0]['city'];
        }
        $this->assign('city_name',$city);
        $this->setLoginHtml();
    }

    public function setHeaderActive($active_page){
        switch ($active_page) {
            case "home":
                $home_active = "active";
                $this->assign('home_active', $home_active);
                break;
            case "course":
                $course_active = "active";
                $this->assign('course_active', $course_active);
                break;
            case "teacher":
                $teacher_active = "active";
                $this->assign('teacher_active', $teacher_active);
                break;
            case "school":
                $school_active = "active";
                $this->assign('school_active', $school_active);
                break;
            case "recruit":
                $recruit_active = "active";
                $this->assign('recruit_active', $recruit_active);
                break;
            case "join":
                $join_active = "active";
                $this->assign('join_active', $join_active);
                break;
            case "about":
                $about_active = "active";
                $this->assign('about_active', $about_active);
                break;
            default:
                break;
        }
        return $this;
    }

    public function setLoginHtml($login_user = "")
    {
        $url =url('user/index/logout');
        if($login_user == "") {
            $login_html = "<a href=\"javascript:void(0)\" id=\"login\">登录</a>
            |
            <a href=\"javascript:void(0)\" id=\"register\">注册</a>";
        } else {
            $login_html = "<label>用户{$login_user}</label>
            |
            <a href=\"".$url."\" id=\"logout\">登出</a>";
        }
        $this->assign('login_html',$login_html);
    }

}