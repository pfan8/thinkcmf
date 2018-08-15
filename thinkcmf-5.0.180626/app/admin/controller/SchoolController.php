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

use app\icr\model\SchoolModel;
use cmf\controller\AdminBaseController;
use think\Collection;
use think\Validate;

/**
 * Class NavController 校区类别管理控制器
 * @package app\admin\controller
 */
class SchoolController extends AdminBaseController
{
    /**
     * 校区管理
     * @adminMenu(
     *     'name'   => '校区管理',
     *     'parent' => 'admin/Setting/default',
     *     'display'=> true,
     *     'hasView'=> true,
     *     'order'  => 30,
     *     'icon'   => '',
     *     'remark' => '校区管理',
     *     'param'  => ''
     * )
     */
    public function index()
    {
        $schoolModel = new SchoolModel();

        $schools = $schoolModel->getSchoolList();
        $this->assign('schools', $schools);
        $this->assign('s_sname','');
        $this->assign('s_location','');
        $this->assign('s_city','');


        $test = preg_match("/^[0-9]+[\.]?[0-9]+,[0-9]+[\.]?[0-9]+$/","11,11.11");
        echo $test;
        return $this->fetch();
    }

    /**
     * 添加校区
     * @adminMenu(
     *     'name'   => '添加校区',
     *     'parent' => 'index',
     *     'display'=> false,
     *     'hasView'=> true,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '添加校区',
     *     'param'  => ''
     * )
     */
    public function add()
    {
        return $this->fetch();
    }

    /**
     * 添加校区提交保存
     * @adminMenu(
     *     'name'   => '添加校区提交保存',
     *     'parent' => 'index',
     *     'display'=> false,
     *     'hasView'=> false,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '添加校区提交保存',
     *     'param'  => ''
     * )
     */
    public function addPost()
    {
        $data = $this->request->post();

        $validate = $this->getSchoolValidate();
        if(!$validate->check($data)){
            $msg = $validate->getError();
            $this->error(lang($msg));
        }
        $school_model = new SchoolModel();
        $school_model->insertSchool($data);
        $this->success(lang('ADD_SUCCESS'), url('school/index'));
    }

    /**
     * 编辑校区
     * @adminMenu(
     *     'name'   => '编辑校区',
     *     'parent' => 'index',
     *     'display'=> false,
     *     'hasView'=> true,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '编辑校区',
     *     'param'  => ''
     * )
     */
    public function edit()
    {
        $school_model = new SchoolModel();
        $id    = $this->request->param("id", 0, 'intval');

        $school = $school_model->getSchoolByID($id);
        $arrNavCat = $school ? $school : [];
        $this->assign($arrNavCat);
        return $this->fetch();
    }


    /**
     * 编辑校区提交保存
     * @adminMenu(
     *     'name'   => '编辑校区提交保存',
     *     'parent' => 'index',
     *     'display'=> false,
     *     'hasView'=> false,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '编辑校区提交保存',
     *     'param'  => ''
     * )
     */
    public function editPost()
    {

        $school_model = new SchoolModel();
        $arrData  = $this->request->post();

        $validate = $this->getSchoolValidate();
        if(!$validate->check($arrData)){
            $msg = $validate->getError();
            $this->error(lang($msg));
        }
        $school_model->updateSchool($arrData);
        $this->success(lang("EDIT_SUCCESS"), url("school/index"));

    }
    /**
     * 删除校区
     * @adminMenu(
     *     'name'   => '删除校区',
     *     'parent' => 'index',
     *     'display'=> false,
     *     'hasView'=> false,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '删除校区',
     *     'param'  => ''
     * )
     */
    public function delete()
    {
        $school_model = new SchoolModel();
        $id    = $this->request->param("id", 0, "intval");

        if (empty($id)) {
            $this->error(lang("NO_ID"));
        }

        $school_model->deleteSchool($id);

        return $this->success(lang("DELETE_SUCCESS"), url("school/index"));

    }

    public function search()
    {
        $school_model = new SchoolModel();
        //通过校区名称
        $s_sname = $this->request->param("s_sname","");
        $school_list = $school_model->getSchoolList();
        if ($s_sname != "")
        {
            $school_list = $school_model->getSchoolByName($s_sname);
            if(empty($school_list))
                $school_list = new Collection();
        }
        //通过校区位置
        $s_location = $this->request->param("s_location","");
        if($s_location != "") {
            $temp_list = $school_model->getSchoolByLocation($s_location);
            $school_list = $this->removeRedundentSchool($school_list, $temp_list);
        }
        //通过校区所在城市查找
        $s_city = $this->request->param("s_city");
        if ($s_city != "")
        {
            $temp_list = $school_model->getSchoolByCity($s_city);
            if(!empty($temp_list))
                $school_list = $this->removeRedundentSchool($school_list, $temp_list);
        }
        $this->assign('schools', $school_list);
        $this->assign('s_sname', $s_sname);
        $this->assign('s_location', $s_location);
        $this->assign('s_city', $s_city);
        return $this->fetch("school/index");
    }

    private function removeRedundentSchool($cl1, $cl2)
    {

        $school_list = new Collection();
        foreach ($cl1 as $school1)
        {
            foreach ($cl2 as $school2)
            {
                if($school1['id'] == $school2['id'])
                {
                    $school_list->push($school1);
                }
            }
        }
        return $school_list;
    }

    private function getSchoolValidate()
    {

        return new SchoolValidate();
    }



}