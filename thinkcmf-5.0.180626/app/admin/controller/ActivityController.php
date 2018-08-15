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
use think\Validate;

/**
 * Class NavController 校区活动类别管理控制器
 * @package app\admin\controller
 */
class ActivityController extends AdminBaseController
{
    /**
     * 校区活动管理
     * @adminMenu(
     *     'name'   => '校区活动管理',
     *     'parent' => 'admin/Setting/default',
     *     'display'=> true,
     *     'hasView'=> true,
     *     'order'  => 30,
     *     'icon'   => '',
     *     'remark' => '校区活动管理',
     *     'param'  => ''
     * )
     */
    public function index()
    {
        $sid     = $this->request->param("sid");
        $schoolModel = new SchoolModel();

        $activitys = $schoolModel->getActivityBySchoolID($sid);
        $this->assign('activitys', $activitys);
        $this->assign('sid', $sid);

        return $this->fetch();

    }

    /**
     * 添加校区活动
     * @adminMenu(
     *     'name'   => '添加校区活动',
     *     'parent' => 'index',
     *     'display'=> false,
     *     'hasView'=> true,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '添加校区活动',
     *     'param'  => ''
     * )
     */
    public function add()
    {
        $sid     = $this->request->param("sid");
        $this->assign('sid', $sid);
        return $this->fetch();
    }

    /**
     * 添加校区活动提交保存
     * @adminMenu(
     *     'name'   => '添加校区活动提交保存',
     *     'parent' => 'index',
     *     'display'=> false,
     *     'hasView'=> false,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '添加校区活动提交保存',
     *     'param'  => ''
     * )
     */
    public function addPost()
    {
        $data = $this->request->post();

        $validate = $this->getActivityValidate();
        if(!$validate->check($data)){
            $msg = $validate->getError();
            $this->error(lang($msg));
        }
        $activity_model = new SchoolModel();
        $sid = $activity_model->getSchoolByID($data['sid']);
        if(empty($sid))
        {
            $this->error(lang("没有该校区"));
        }
        $activity_model->insertActivity($data);
        $this->success(lang('ADD_SUCCESS'), url('activity/index',array('sid' => $data['sid'])));
    }

    /**
     * 编辑预定
     * @adminMenu(
     *     'name'   => '编辑预定',
     *     'parent' => 'index',
     *     'display'=> false,
     *     'hasView'=> true,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '编辑预定',
     *     'param'  => ''
     * )
     */
    public function edit()
    {
        $activity_model = new SchoolModel();
        $id    = $this->request->param("id", 0, 'intval');

        $activity = $activity_model->getActivityByID($id);
        $arrNavCat = $activity ? $activity : [];
        $this->assign($arrNavCat);
        return $this->fetch();
    }


    /**
     * 编辑预定提交保存
     * @adminMenu(
     *     'name'   => '编辑预定提交保存',
     *     'parent' => 'index',
     *     'display'=> false,
     *     'hasView'=> false,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '编辑预定提交保存',
     *     'param'  => ''
     * )
     */
    public function editPost()
    {

        $activity_model = new SchoolModel();
        $arrData  = $this->request->post();

        $validate = $this->getActivityValidate();
        if(!$validate->check($arrData)){
            $msg = $validate->getError();
            $this->error(lang($msg));
        }
        $sid = $activity_model->getSchoolByID($arrData['sid']);
        if(empty($sid))
        {
            $this->error(lang("没有该校区"));
        }
        $activity_model->updateActivity($arrData);
        $this->success(lang("EDIT_SUCCESS"), url('activity/index',array('sid' => $arrData['sid'])));

    }

    /**
     * 删除校区活动
     * @adminMenu(
     *     'name'   => '删除校区活动',
     *     'parent' => 'index',
     *     'display'=> false,
     *     'hasView'=> false,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '删除校区活动',
     *     'param'  => ''
     * )
     */
    public function delete()
    {
        $school_model = new SchoolModel();
        $id    = $this->request->param("id", 0, "intval");
        $sid    = $this->request->param("sid", 0, "intval");

        if (empty($id)) {
            $this->error(lang("NO_ID"));
        }

        $school_model->deleteActivity($id);

        return $this->success(lang("DELETE_SUCCESS"), url("activity/index",array('sid' => $sid)));

    }

    private function getActivityValidate()
    {
        //验证
        $rule = [
            'sid'  => 'require|number',
            'name'   => 'require',
        ];

        $msg = [
            'sid.require' => '校区ID必须',
            'sid.number' => '校区ID必须是数字',
            'name.require' => '校区活动必须',
        ];
        return new Validate($rule, $msg);
    }

}