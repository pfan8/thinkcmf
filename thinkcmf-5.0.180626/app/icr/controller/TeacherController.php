<?php

/**
 * Created by PhpStorm.
 * User: pfan8
 * Date: 2018/7/30
 * Time: 22:40
 */
namespace app\icr\controller;

use app\icr\model\TeacherModel;
use cmf\controller\HomeBaseController;

class TeacherController extends HomebaseController{

    // 首页
    public function index(){
        $head_controller = new HeadController();
        $head_controller->setHeaderActive("teacher");
        $teacher_model = new TeacherModel();
        $data = $this->request->param();
        $page = empty($data['page']) ? 1 : $data['page'];
        $limit = $this->getTeacherLimitFromPage($page);
        $teacher = $teacher_model->getTeacherList($limit);
        $this->complementTeacher($teacher);
        $this->assign('page',$page);
        $this->assign('teacher', $teacher);
        return $this->fetch(':teachers');
    }

    /**
     * 添加教师
     * @param $data
     * @return
     */
    public function insertTeacher()
    {
        $data = [
            'name' => $_GET['name'],
            'position' => $_GET['position'],
            'resume' => $_GET['resume'],
            'phone' => $_GET['phone'],
            'gender' => $_GET['gender'],
            'age' => $_GET['age'],
        ];

        $teacher_model = new TeacherModel();
        $teacher_model->insertTeacher($data);
    }

    /**
     * 修改教师
     * @param $data
     * @return
     */
    public function updateTeacher()
    {
        $data = [
            'id' => $_GET['id'],
            'name' => $_GET['name'],
            'position' => $_GET['position'],
            'resume' => $_GET['resume'],
            'phone' => $_GET['phone'],
            'gender' => $_GET['gender'],
            'age' => $_GET['age'],
            'icon' => $_GET['icon'],
        ];

        $teacher_model = new TeacherModel();
        $teacher_model->updateTeacher($data);
    }

    /**
     * 删除教师
     * @param $data
     * @return
     */
    public function deleteTeacher()
    {
        $teacher_model = new TeacherModel();
        $teacher_model->deleteTeacher($_GET['id']);
    }

    /**
     * 通过id查询教师
     * @param $data
     * @return
     */
    public function getTeacherByID()
    {
        $teacher_model = new TeacherModel();
        return $teacher_model->getTeacherByID($_GET['id']);
    }

    /**
     * 通过姓名查询教师
     * @param $data
     * @return
     */
    public function getTeacherByName()
    {
        $teacher_model = new TeacherModel();
        return $teacher_model->getTeacherByName($_GET['name']);
    }

    /**
     * 通过职位查询教师
     * @param $data
     * @return
     */
    public function getTeacherByPosition()
    {
        $teacher_model = new TeacherModel();
        return $teacher_model->getTeacherByPosition($_GET['position']);
    }

    /**
     * 通过简历查询教师
     * @param $data
     * @return
     */
    public function getTeacherByResume()
    {
        $teacher_model = new TeacherModel();
        return $teacher_model->getTeacherByResume($_GET['resume']);
    }

    /**
     * 通过电话查询教师
     * @param $data
     * @return
     */
    public function getTeacherByPhone()
    {
        $teacher_model = new TeacherModel();
        return $teacher_model->getTeacherByPhone($_GET['phone']);
    }

    /**
     * 通过性别查询教师
     * @param $data
     * @return
     */
    public function getTeacherByGender()
    {
        $teacher_model = new TeacherModel();
        return $teacher_model->getTeacherByGender($_GET['gender']);
    }

    /**
     * 通过年龄查询教师
     * @param $data
     * @return
     */
    public function getTeacherByAge($age)
    {
        $teacher_model = new TeacherModel();
        return $teacher_model->getTeacherByAge($_GET['age']);
    }

    /**
     * 查询不大于指定年龄教师
     * @param $data
     * @return
     */
    public function getTeacherELTAge($age)
    {
        $teacher_model = new TeacherModel();
        return $teacher_model->getTeacherELTAge($_GET['age']);
    }

    /**
     * 查询不小于指定年龄教师
     * @param $data
     * @return
     */
    public function getTeacherEGTAge($age)
    {
        $teacher_model = new TeacherModel();
        return $teacher_model->getTeacherEGTAge($_GET['age']);
    }
    /**
     * 通过课程查询教师
     * @param $data
     * @return
     */
    public function getTeacherByCourse()
    {
        $cid = $_GET['cid'];
        $teacher_model = new TeacherModel();
        return $teacher_model->getTeacherByCourse($cid);
    }

    private function complementTeacher(&$teachers)
    {
        for ($i = 0; $i < count($teachers); $i++) {
            $teacher = $teachers->shift();
            if (empty($teacher['icon'])) {
                $teacher['icon'] = '/themes/RY/icr/imgs/timg.jpg';
            }
            $this->transformToHtml($teacher);
            $teachers->push($teacher);
        }
        while (count($teachers) < 6) {
            $teacher = [
                'icon' => '/themes/RY/icr/imgs/timg.jpg',
                'name' => '待添加',
                'resume' => '待添加',
                'position' => '待添加',
                'idea' => '待添加',
            ];
            $this->transformToHtml($teacher);
            $teachers->push($teacher);
        }
    }

    private function transformToHtml(&$teacher)
    {
        $teacher['position'] = "——" . $teacher['position'];
//        $resume_array = explode('\n',$teacher['resume']);
//        $resume = "";
//        for($i = 0; $i < count($resume_array);$i++)
//        {
//            $resume = $resume . $resume_array[$i] + "<br/>";
//        }
//        echo $resume;
//        $teacher['resume'] = $resume;
    }

    private function getTeacherLimitFromPage($page)
    {
        switch ($page) {
            case 1:
                return "0,6";
            case 2:
                return "6,12";
            case 3:
                return "12,18";
            case 4:
                return "18,24";
            default:
                return 100;
        }
    }
}