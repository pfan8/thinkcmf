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
 * Class NavController 校区图片类别管理控制器
 * @package app\admin\controller
 */
class PictureController extends AdminBaseController
{
    /**
     * 校区图片管理
     * @adminMenu(
     *     'name'   => '校区图片管理',
     *     'parent' => 'admin/Setting/default',
     *     'display'=> true,
     *     'hasView'=> true,
     *     'order'  => 30,
     *     'icon'   => '',
     *     'remark' => '校区图片管理',
     *     'param'  => ''
     * )
     */
    public function index()
    {
        $sid     = $this->request->param("sid");
        $schoolModel = new SchoolModel();

        $pictures = $schoolModel->getPictureListBySchoolID($sid);
        $this->assign('pictures', $pictures);
        $this->assign('sid', $sid);

        return $this->fetch();

    }

    /**
     * 添加校区图片
     * @adminMenu(
     *     'name'   => '添加校区图片',
     *     'parent' => 'index',
     *     'display'=> false,
     *     'hasView'=> true,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '添加校区图片',
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
     * 添加校区图片提交保存
     * @adminMenu(
     *     'name'   => '添加校区图片提交保存',
     *     'parent' => 'index',
     *     'display'=> false,
     *     'hasView'=> false,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '添加校区图片提交保存',
     *     'param'  => ''
     * )
     */
    public function addPost()
    {
        $data = $this->request->post();

        $validate = $this->getPictureValidate();
        if(!$validate->check($data)){
            $msg = $validate->getError();
            $this->error(lang($msg));
        }
        $school_model = new SchoolModel();
        $school_model->insertPicture($data);
        $this->success(lang('ADD_SUCCESS'), url('picture/index', array('sid'=>$data['sid'])));
    }

    /**
     * 删除校区图片
     * @adminMenu(
     *     'name'   => '删除校区图片',
     *     'parent' => 'index',
     *     'display'=> false,
     *     'hasView'=> false,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '删除校区图片',
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

        $school_model->deletePicture($id);

        return $this->success(lang("DELETE_SUCCESS"), url("picture/index", array('sid' => $sid)));

    }

    private function getPictureValidate()
    {
        //验证
        $rule = [
            'sid'  => 'require|number',
            'url'   => 'require',
        ];

        $msg = [
            'sid.require' => '校区ID必须',
            'sid.number' => '校区ID必须是数字',
            'url.require' => '校区图片必须',
        ];
        return new Validate($rule, $msg);
    }

}