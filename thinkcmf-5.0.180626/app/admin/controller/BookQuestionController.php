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
use cmf\controller\AdminBaseController;
use think\Collection;
use think\Validate;

/**
 * Class NavController 预定问题类别管理控制器
 * @package app\admin\controller
 */
class BookQuestionController extends AdminBaseController
{
    /**
     * 预定问题管理
     * @adminMenu(
     *     'name'   => '预定问题管理',
     *     'parent' => 'admin/Setting/default',
     *     'display'=> true,
     *     'hasView'=> true,
     *     'order'  => 30,
     *     'icon'   => '',
     *     'remark' => '预定问题管理',
     *     'param'  => ''
     * )
     */
    public function index()
    {
        $book_questionModel = new CourseModel();

        $book_questions = $book_questionModel->getBookQuestionList();
        $this->assign('book_questions', $book_questions);

        return $this->fetch();

    }

    /**
     * 添加预定问题
     * @adminMenu(
     *     'name'   => '添加预定问题',
     *     'parent' => 'index',
     *     'display'=> false,
     *     'hasView'=> true,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '添加预定问题',
     *     'param'  => ''
     * )
     */
    public function add()
    {
        return $this->fetch();
    }

    /**
     * 添加预定问题提交保存
     * @adminMenu(
     *     'name'   => '添加预定问题提交保存',
     *     'parent' => 'index',
     *     'display'=> false,
     *     'hasView'=> false,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '添加预定问题提交保存',
     *     'param'  => ''
     * )
     */
    public function addPost()
    {
        $data = $this->request->post();
        $book_question_model = new CourseModel();
        $book_question_model->insertBookQuestion($data);
        $this->success(lang('ADD_SUCCESS'), url('book_question/index'));
    }

    /**
     * 编辑预定问题
     * @adminMenu(
     *     'name'   => '编辑预定问题',
     *     'parent' => 'index',
     *     'display'=> false,
     *     'hasView'=> true,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '编辑预定问题',
     *     'param'  => ''
     * )
     */
    public function edit()
    {
        $book_question_model = new CourseModel();
        $id    = $this->request->param("id", 0, 'intval');

        $book_question = $book_question_model->getBookQuestionByID($id);
        $this->assign($book_question);
        return $this->fetch();
    }


    /**
     * 编辑预定问题提交保存
     * @adminMenu(
     *     'name'   => '编辑预定问题提交保存',
     *     'parent' => 'index',
     *     'display'=> false,
     *     'hasView'=> false,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '编辑预定问题提交保存',
     *     'param'  => ''
     * )
     */
    public function editPost()
    {

        $book_question_model = new CourseModel();
        $arrData  = $this->request->post();

        $book_question_model->updateBookQuestion($arrData);
        $this->success(lang("EDIT_SUCCESS"), url("book_question/index"));

    }

    /**
     * 删除预定问题
     * @adminMenu(
     *     'name'   => '删除预定问题',
     *     'parent' => 'index',
     *     'display'=> false,
     *     'hasView'=> false,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '删除预定问题',
     *     'param'  => ''
     * )
     */
    public function delete()
    {
        $book_question_model = new CourseModel();
        $id    = $this->request->param("id", 0, "intval");

        if (empty($id)) {
            $this->error(lang("NO_ID"));
        }

        $book_question_model->deleteBookQuestion($id);

        return $this->success(lang("DELETE_SUCCESS"), url("book_question/index"));

    }

}