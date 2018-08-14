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

use app\user\model\UserModel;
use app\icr\model\CourseModel;
use app\icr\model\TeacherModel;
use cmf\controller\AdminBaseController;
use think\Collection;
use think\Validate;

/**
 * Class NavController 教师类别管理控制器
 * @package app\admin\controller
 */
class TeacherController extends AdminBaseController
{
    /**
     * 教师管理
     * @adminMenu(
     *     'name'   => '教师管理',
     *     'parent' => 'admin/Setting/default',
     *     'display'=> true,
     *     'hasView'=> true,
     *     'order'  => 30,
     *     'icon'   => '',
     *     'remark' => '教师管理',
     *     'param'  => ''
     * )
     */
    public function index()
    {
        $teacherModel = new TeacherModel();

        $teachers = $teacherModel->getTeacherList();
        $this->transformToHtml($teachers);
        $this->setCIDList_Html($teachers);
        $this->assign('teachers', $teachers);
        $this->assign('teacher_name','');
        $this->assign('cid','');
        $this->assign('option_html',$this->getOptionHtml());
        return $this->fetch();

    }

    /**
     * 添加教师
     * @adminMenu(
     *     'name'   => '添加教师',
     *     'parent' => 'index',
     *     'display'=> false,
     *     'hasView'=> true,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '添加教师',
     *     'param'  => ''
     * )
     */
    public function add()
    {
        $this->assign('option_html',$this->getOptionHtml());
        return $this->fetch();
    }

    /**
     * 添加教师提交保存
     * @adminMenu(
     *     'name'   => '添加教师提交保存',
     *     'parent' => 'index',
     *     'display'=> false,
     *     'hasView'=> false,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '添加教师提交保存',
     *     'param'  => ''
     * )
     */
    public function addPost()
    {
        $data = $this->request->post();

        $validate = $this->getTeacherValidate();
        if(!$validate->check($data)){
            $msg = $validate->getError();
            $this->error(lang($msg), url('teacher/add'));
        }
        $teacher_model = new TeacherModel();
        $tid = $teacher_model->insertTeacher($data);
        $course_model = new CourseModel();
        if ($data['cid_list'] != "")
        {
            $cid_list = explode(",",$data['cid_list']);
            foreach ($cid_list as $cid)
            {
                $is_course_exist = $course_model->getCourseByID($cid);
                if (empty($is_course_exist))
                    return $this->error(lang("没有该课程"), url('teacher/add'));
                else
                {
                    $teacher_model->addCourseToTeacher($tid, $cid);
                }
            }
        }
        $this->success(lang('ADD_SUCCESS'), url('teacher/index'));
    }

    /**
     * 编辑教师
     * @adminMenu(
     *     'name'   => '编辑教师',
     *     'parent' => 'index',
     *     'display'=> false,
     *     'hasView'=> true,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '编辑教师',
     *     'param'  => ''
     * )
     */
    public function edit()
    {
        $teacher_model = new TeacherModel();
        $id    = $this->request->param("id", 0, 'intval');

        $teacher = $teacher_model->getTeacherByID($id);
        $arrNavCat = $teacher ? $teacher : [];
        //前端默认选择教师等级
        $option_html = $this->getOptionHtml($teacher['gender']);
        $this->assign('option_html',$option_html);

        $this->setCIDList($arrNavCat);
        $this->assign($arrNavCat);
        return $this->fetch();
    }


    /**
     * 编辑教师提交保存
     * @adminMenu(
     *     'name'   => '编辑教师提交保存',
     *     'parent' => 'index',
     *     'display'=> false,
     *     'hasView'=> false,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '编辑教师提交保存',
     *     'param'  => ''
     * )
     */
    public function editPost()
    {

        $teacher_model = new TeacherModel();
        $arrData  = $this->request->post();

        $validate = $this->getTeacherValidate();
        if(!$validate->check($arrData)){
            $msg = $validate->getError();
            $this->error(lang($msg), url('teacher/edit',array('id' => $arrData['id'])));
        }
        $teacher_model->updateTeacher($arrData);
        $course_model = new CourseModel();
        $teacher_model->deleteCourses($arrData['id']);
        $cid_list = explode(",",$arrData['cid_list']);
        if (!empty($cid_list['id']))
        {
            foreach ($cid_list as $cid)
            {
                $is_course_exist = $course_model->getCourseByID($cid);
                if (empty($is_course_exist))
                    return $this->error(lang("没有该课程"), url('teacher/index'));
                else {
                    $teacher_model->addCourseToTeacher($arrData['id'], $cid);
                }
            }
        }
        $this->success(lang("EDIT_SUCCESS"), url("teacher/index"));

    }

    /**
     * 删除教师
     * @adminMenu(
     *     'name'   => '删除教师',
     *     'parent' => 'index',
     *     'display'=> false,
     *     'hasView'=> false,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '删除教师',
     *     'param'  => ''
     * )
     */
    public function delete()
    {
        $teacher_model = new TeacherModel();
        $id    = $this->request->param("id", 0, "intval");

        if (empty($id)) {
            $this->error(lang("NO_ID"));
        }

        $teacher_model->deleteTeacher($id);
        $teacher_model->deleteCourses($id);
        return $this->success(lang("DELETE_SUCCESS"), url("teacher/index"));

    }

    public function search()
    {
        $teacher_model = new TeacherModel();
        //通过教师姓名
        $teacher_name = $this->request->param("teacher_name","");
        $teacher_list = $teacher_model->getTeacherList();
        if ($teacher_name != "")
        {
            $teacher_list = $teacher_model->getTeacherByName($teacher_name);
            if(empty($teacher_list))
                $teacher_list = new Collection();
        }
        //通过课程ID
        $cid = $this->request->param("cid","");
        if($cid != "") {
            $temp_list = $teacher_model->getTeacherByCourse($cid);
            $teacher_list = $this->removeRedundentTeacher($teacher_list, $temp_list);
        }
        //通过教师性别查找
        $teacher_gender = $this->request->param("teacher_gender");
        if ($teacher_gender != "请选择")
        {
            $temp_list = $teacher_model->getTeacherByGender($teacher_gender);
            if(!empty($temp_list))
                $teacher_list = $this->removeRedundentTeacher($teacher_list, $temp_list);
        }
        $this->transformToHtml($teacher_list);
        $this->setCIDList_Html($teacher_list);
        $this->assign('teachers', $teacher_list);
        $this->assign('teacher_name', $teacher_name);
        $this->assign('cid', $cid);
        $this->assign('teacher_gender', $teacher_gender);
        $this->assign('option_html', $this->getOptionHtml($teacher_gender));
        return $this->fetch("teacher/index");
    }

    private function removeRedundentTeacher($cl1, $cl2)
    {

        $teacher_list = new Collection();
        foreach ($cl1 as $teacher1)
        {
            foreach ($cl2 as $teacher2)
            {
                if($teacher1['id'] == $teacher2['id'])
                {
                    $teacher_list->push($teacher1);
                }
            }
        }
        return $teacher_list;
    }

    private function getTeacherValidate()
    {
        //验证
        $rule = [
            'name'  => 'require',
            'position'   => 'require',
            'gender'   => 'require|number|between:1,2',
            'phone'  => 'number',
            'age'  => 'number',
        ];

        $msg = [
            'name.require' => '教师姓名必须',
            'position.require' => '职位必须',
            'gender.require' => '教师性别必须',
            'gender.number'        => '教师性别只能选数字',
            'gender.between'  => '教师性别只能选1-2之间',
            'phone.number'  => "电话只能是数字",
            'age.number'  => "年龄只能是数字",
        ];
        return new Validate($rule, $msg);
    }

    private function getOptionHtml($gender=0)
    {
        $option_html = "<option>请选择</option>";
        for($op = 1; $op <= 2; $op++)
        {
            if ($op == $gender)
                $option_html .= "<option selected=\"selected\">" . $op . "</option>";
            else
                $option_html .= "<option>" . $op . "</option>";
        }
        return $option_html;
    }

    private function transformToHtml(&$teachers)
    {
        foreach ($teachers as $item)
        {
            $teachers->shift();
            $teacher_model = new TeacherModel();
            $teacher_model->transformResumeToHtml($item);
            $teachers->push($item);
        }
    }

    private function setCIDList(&$teachers)
    {
        $course_model = new CourseModel();
        if (empty($teachers['id'])) {
            $new_teachers = new Collection();
            foreach ($teachers as $teacher) {
                $cid_list = "";
                $courses = $course_model->getCourseByTeacher($teacher['id']);
                if (empty($courses['id'])) {
                    foreach ($courses as $course) {
                        $cid_list .= $course['id'] . ",";
                    }
                } else {
                    $cid_list .= $courses['id'];
                }
                $teacher['cid_list'] = $cid_list;
                $new_teachers->push($teacher);
            }
            $teachers = $new_teachers;
        } else {
            $courses = $course_model->getCourseByTeacher($teachers['id']);
            $cid_list = "";
            if (empty($courses['id'])) {
                foreach ($courses as $course) {
                    $cid_list .= $course['id'] . ",";
                }
            } else {
                $cid_list .= $courses['id'];
            }
            $teachers['cid_list'] = $cid_list;
        }
    }

    private function setCIDList_Html(&$teachers)
    {
        $course_model = new CourseModel();
        if (empty($teachers['id']))
        {
            $new_teachers = new Collection();
            foreach ($teachers as $teacher)
            {
                $cid_list = "";
                $courses = $course_model->getCourseByTeacher($teacher['id']);
                if(empty($courses['id']))
                {
                    foreach ($courses as $course)
                    {
                        $cid_list .= $this->getCourseHref($course['id']) . " ";
                    }
                } else {
                    $cid_list .= $this->getCourseHref($courses['id']);
                }
                $teacher['cid_list'] = $cid_list;
                $new_teachers->push($teacher);
            }
            $teachers = $new_teachers;
        } else  {
            $courses = $course_model->getCourseByTeacher($teachers['id']);
            $cid_list = "";
            if(empty($courses['id']))
            {
                foreach ($courses as $course)
                {
                    $cid_list .= $this->getCourseHref($course['id']) . " ";
                }
            } else {
                $cid_list .= $this->getCourseHref($courses['id']);
            }
            $teachers['cid_list'] = $cid_list;
        }
    }

    private function getCourseHref($cid)
    {
        $cid_href = "<a href=\"/admin/course/index\">$cid</a>";
        return $cid_href;
    }

}