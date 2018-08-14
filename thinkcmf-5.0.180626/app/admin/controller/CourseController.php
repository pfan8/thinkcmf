<?php
// +----------------------------------------------------------------------
// | ThinkCMF [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2018 http://www.thinkcmf.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: kane <chengjin005@163.com> 小夏 < 449134904@qq.com>
// +----------------------------------------------------------------------
namespace app\admin\controller;

use app\icr\model\CourseModel;
use app\icr\model\TeacherModel;
use cmf\controller\AdminBaseController;
use think\Collection;
use think\Validate;

/**
 * Class NavController 课程类别管理控制器
 * @package app\admin\controller
 */
class CourseController extends AdminBaseController
{
    /**
     * 课程管理
     * @adminMenu(
     *     'name'   => '课程管理',
     *     'parent' => 'admin/Setting/default',
     *     'display'=> true,
     *     'hasView'=> true,
     *     'order'  => 30,
     *     'icon'   => '',
     *     'remark' => '课程管理',
     *     'param'  => ''
     * )
     */
    public function index()
    {
        $courseModel = new CourseModel();

        $courses = $courseModel->getCourseList();
        $this->convertCourses($courses);
        $this->assign('courses', $courses);
        $this->assign('course_name','');
        $this->assign('teacher_name','');
        $this->assign('option_html',$this->getOptionHtml());

        return $this->fetch();

    }

    /**
     * 添加课程
     * @adminMenu(
     *     'name'   => '添加课程',
     *     'parent' => 'index',
     *     'display'=> false,
     *     'hasView'=> true,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '添加课程',
     *     'param'  => ''
     * )
     */
    public function add()
    {
        $this->assign('option_html',$this->getOptionHtml());
        return $this->fetch();
    }

    /**
     * 添加课程提交保存
     * @adminMenu(
     *     'name'   => '添加课程提交保存',
     *     'parent' => 'index',
     *     'display'=> false,
     *     'hasView'=> false,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '添加课程提交保存',
     *     'param'  => ''
     * )
     */
    public function addPost()
    {
        $data = $this->request->post();

        $validate = $this->getCourseValidate();
        if(!$validate->check($data)){
            $msg = $validate->getError();
            $this->error(lang($msg), url('course/add'));
        }
        $course_model = new CourseModel();
        $course_model->insertCourse($data);
        $this->success(lang('ADD_SUCCESS'), url('course/index'));
    }

    /**
     * 编辑课程
     * @adminMenu(
     *     'name'   => '编辑课程',
     *     'parent' => 'index',
     *     'display'=> false,
     *     'hasView'=> true,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '编辑课程',
     *     'param'  => ''
     * )
     */
    public function edit()
    {
        $course_model = new CourseModel();
        $id    = $this->request->param("id", 0, 'intval');

        $course = $course_model->getCourseByID($id);
        $arrNavCat = $course ? $course : [];
        //前端默认选择课程等级
        $option_html = $this->getOptionHtml($course['level']);
        $this->assign('option_html',$option_html);
        $this->convertLevel($course);
        $this->assign($arrNavCat);
        return $this->fetch();
    }


    /**
     * 编辑课程提交保存
     * @adminMenu(
     *     'name'   => '编辑课程提交保存',
     *     'parent' => 'index',
     *     'display'=> false,
     *     'hasView'=> false,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '编辑课程提交保存',
     *     'param'  => ''
     * )
     */
    public function editPost()
    {

        $course_model = new CourseModel();
        $arrData  = $this->request->post();

        $validate = $this->getCourseValidate();
        if(!$validate->check($arrData)){
            $msg = $validate->getError();
//            $this->assign($arrData);
            $this->error(lang($msg), url('course/edit',array('id' => $arrData['id'])));
        }
        $course_model->updateCourse($arrData);
        $this->success(lang("EDIT_SUCCESS"), url("course/index"));

    }

    /**
     * 删除课程
     * @adminMenu(
     *     'name'   => '删除课程',
     *     'parent' => 'index',
     *     'display'=> false,
     *     'hasView'=> false,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '删除课程',
     *     'param'  => ''
     * )
     */
    public function delete()
    {
        $course_model = new CourseModel();
        $id    = $this->request->param("id", 0, "intval");

        if (empty($id)) {
            $this->error(lang("NO_ID"));
        }

        $course_model->deleteCourse($id);

        return $this->success(lang("DELETE_SUCCESS"), url("course/index"));

    }

    public function search()
    {
        $course_model = new CourseModel();
        $teacher_model = new TeacherModel();
        //通过课程名称查找
        $course_name = $this->request->param("course_name","");
        $course_list = $course_model->getCourseList();
        if ($course_name != "")
        {
            $course_list = $course_model->getCourseByName($course_name)->toArray();
            if(empty($course_list))
                $course_list = [];
        }
        //通过教师名称查找
        $teacher_name = $this->request->param("teacher_name","");
        if($teacher_name != "") {
            $tid_list = $teacher_model->getTeacherByName($teacher_name);
            if (!empty($tid_list))
            {
                foreach ($tid_list as $tid)
                {
                    $temp_list = $course_model->getCourseByTeacher($tid);
                    if(!empty($temp_list))
                        $course_list = $this->removeRedundentCourse($course_list, $temp_list);
                }
            }
        }
        //通过课程等级查找
        $course_level = $this->request->param("course_level",0);
        if ($course_level != "请选择")
        {
            $temp_list = $course_model->getCourseByLevel($course_level);
            if(!empty($temp_list))
//                $course_list->intersect($temp_list);
                $course_list = $this->removeRedundentCourse($course_list, $temp_list);
        }
        $this->convertCourses($course_list);
        $this->assign('courses', $course_list);
        $this->assign('course_name', $course_name);
        $this->assign('teacher_name', $teacher_name);
        $this->assign('course_level', $course_level);
        $this->assign('option_html', $this->getOptionHtml($course_level));
        return $this->fetch("course/index");
    }

    private function removeRedundentCourse($cl1, $cl2)
    {

        $course_list = [];
        foreach ($cl1 as $course1)
        {
            foreach ($cl2 as $course2)
            {
                if($course1['id'] == $course2['id'])
                {
                    $course_list[] = $course1;
                }
            }
        }
        return $course_list;
    }

    private function getCourseValidate()
    {
        //验证
        $rule = [
            'name'  => 'require',
            'level'   => 'require',
        ];

        $msg = [
            'name.require' => '课程名必须',
            'level.require'   => '课程等级必须',
        ];
        return new Validate($rule, $msg);
    }

    private function getOptionHtml($level=0)
    {
        $option_html = "<option>请选择</option>";
        $course_model = new CourseModel();
        $levels = $course_model->getLevelList();
        $max_level = count($levels);
        for($i = 0; $i < $max_level; $i++)
        {
            $op = $course_model->getCourseCategoryByID($levels[$i]['category_id'])['name'] . " " . $levels[$i]['level'];
            if ($levels[$i]['id'] == $level)
                $option_html .= "<option selected=\"selected\" value=".$levels[$i]['id'].">" . $op . "</option>";
            else
                $option_html .= "<option value=".$levels[$i]['id'].">" . $op . "</option>";
        }
        return $option_html;
    }

    public function convertCourses(&$courses)
    {
        $result = new Collection();
        foreach ($courses as $course) {
            $this->convertLevel($course);
            $result->push($course);
        }
        $courses = $result;
    }

    public function convertLevel(&$course){
        $course_model = new CourseModel();
        $levels = $course_model->getLevelList();
        $lid = $course['level'];
        foreach ($levels as $level) {
            if ($level['id'] == $lid)
                $course['level'] = $course_model->getCourseCategoryByID($level['category_id'])['name'] . " " . $level['level'];
        }
    }

}