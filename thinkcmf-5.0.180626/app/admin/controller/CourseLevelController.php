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
use cmf\controller\AdminBaseController;
use think\Validate;

/**
 * Class NavController 课程等级类别管理控制器
 * @package app\admin\controller
 */
class CourseLevelController extends AdminBaseController
{
    /**
     * 课程等级管理
     * @adminMenu(
     *     'name'   => '课程等级管理',
     *     'parent' => 'admin/Setting/default',
     *     'display'=> true,
     *     'hasView'=> true,
     *     'order'  => 30,
     *     'icon'   => '',
     *     'remark' => '课程等级管理',
     *     'param'  => ''
     * )
     */
    public function index()
    {
        $courseModel = new CourseModel();

        $course_categorys = $courseModel->getCategoryList();
        $this->assign('course_categorys', $course_categorys);

        return $this->fetch();

    }

    /**
     * 添加课程等级
     * @adminMenu(
     *     'name'   => '添加课程等级',
     *     'parent' => 'index',
     *     'display'=> false,
     *     'hasView'=> true,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '添加课程等级',
     *     'param'  => ''
     * )
     */
    public function add()
    {
        $category_option = $this->getCategoryOption();
        $this->assign('category_option', $category_option);
        return $this->fetch();
    }

    /**
     * 添加课程等级提交保存
     * @adminMenu(
     *     'name'   => '添加课程等级提交保存',
     *     'parent' => 'index',
     *     'display'=> false,
     *     'hasView'=> false,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '添加课程等级提交保存',
     *     'param'  => ''
     * )
     */
    public function addPost()
    {
        $data = $this->request->post();

        if ($data['category_id'] == "请选择")
            $this->error("一类等级必须",url('CourseLevel/add'));
        if (!preg_match('/^\d+$/',$data['level']))
            $this->error("二类等级必须是数字",url('CourseLevel/add'));

        $course_model = new CourseModel();
        $course_model->insertCourseLevel($data);
        $this->success(lang('ADD_SUCCESS'), url('CourseLevel/index'));
    }

    public function editLevels()
    {
        $data = $this->request->param();
        $category_id = empty($data['category_id']) ? 0 : $data['category_id'];
        $course_model = new CourseModel();
        $course_levels = $course_model->getCourseLevelsByCategoryID($category_id);
        $this->assign('course_levels',$course_levels);
        return $this->fetch("edit_levels");
    }

    public function edit()
    {
        $data = $this->request->param();
        $category_id = empty($data['category_id']) ? 0 : $data['category_id'];
        $course_model = new CourseModel();
        $course_category = $course_model->getCategoryByID($category_id);
        $this->assign($course_category);
        return $this->fetch();
    }

    public function editPost()
    {

        $course_model = new CourseModel();
        $arrData  = $this->request->post();

        $course_model->updateCategory($arrData);
        $this->success(lang("EDIT_SUCCESS"), url("CourseLevel/index"));

    }

    public function editLevelPost()
    {

        $course_model = new CourseModel();
        $arrData  = $this->request->post();
        $levels = $course_model->getLevelList();
        foreach ($levels as $level) {
            if (!empty($arrData[$level['id']])) {
                $course_model->updateLevelNameByID($level['id'],$arrData[$level['id']]);
            }
        }

        $this->success(lang("EDIT_SUCCESS"), url("CourseLevel/index"));

    }

    /**
     * 删除课程等级
     * @adminMenu(
     *     'name'   => '删除课程等级',
     *     'parent' => 'index',
     *     'display'=> false,
     *     'hasView'=> false,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '删除课程等级',
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

        $course_model->deleteLevel($id);

        return $this->success(lang("DELETE_SUCCESS"), url("CourseLevel/index"));

    }

    private function getCategoryOption($category_id=0)
    {
        $option_html = "<option>请选择</option>";
        $course_model = new CourseModel();
        $categorys = $course_model->getCategoryList();
        $max_category = count($categorys);
        for($i = 0; $i < $max_category; $i++)
        {
            $op = $categorys[$i]['name'];
            if ($categorys[$i]['id'] == $category_id)
                $option_html .= "<option selected=\"selected\" value=".$categorys[$i]['id'].">" . $op . "</option>";
            else
                $option_html .= "<option value=".$categorys[$i]['id'].">" . $op . "</option>";
        }
        return $option_html;
    }

}