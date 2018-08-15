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
 * Class NavController 预定类别管理控制器
 * @package app\admin\controller
 */
class BookController extends AdminBaseController
{
    /**
     * 预定管理
     * @adminMenu(
     *     'name'   => '预定管理',
     *     'parent' => 'admin/Setting/default',
     *     'display'=> true,
     *     'hasView'=> true,
     *     'order'  => 30,
     *     'icon'   => '',
     *     'remark' => '预定管理',
     *     'param'  => ''
     * )
     */
    public function index()
    {
        $bookModel = new CourseModel();

        $books = $bookModel->getBookList();
        $this->assign('books', $books);
        $this->assign('cid','');
        $this->assign('phone','');
        $this->assign('option_html',$this->getOptionHtml());

        return $this->fetch();

    }

    /**
     * 添加预定
     * @adminMenu(
     *     'name'   => '添加预定',
     *     'parent' => 'index',
     *     'display'=> false,
     *     'hasView'=> true,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '添加预定',
     *     'param'  => ''
     * )
     */
    public function add()
    {
        $this->assign('option_html',$this->getOptionHtml());
        return $this->fetch();
    }

    /**
     * 添加预定提交保存
     * @adminMenu(
     *     'name'   => '添加预定提交保存',
     *     'parent' => 'index',
     *     'display'=> false,
     *     'hasView'=> false,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '添加预定提交保存',
     *     'param'  => ''
     * )
     */
    public function addPost()
    {
        $data = $this->request->post();

        $validate = $this->getBookValidate();
        if(!$validate->check($data)){
            $msg = $validate->getError();
            $this->error(lang($msg));
        }
        $book_model = new CourseModel();
        $course_model = new CourseModel();
        $is_course_exist = $course_model->getCourseByID($data['cid']);
        if(!$is_course_exist)
            return $this->error(lang("没有该课程"));
        $book_model->bookCourse($data);
        $this->success(lang('ADD_SUCCESS'), url('book/index'));
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
        $book_model = new CourseModel();
        $id    = $this->request->param("id", 0, 'intval');

        $book = $book_model->getBookByID($id);
        $arrNavCat = $book ? $book : [];
        //前端默认选择预定等级
        $option_html = $this->getOptionHtml($book['has_notified']);
        $this->assign('option_html',$option_html);
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

        $book_model = new CourseModel();
        $arrData  = $this->request->post();

        $validate = $this->getBookValidate();
        if(!$validate->check($arrData)){
            $msg = $validate->getError();
            $this->error(lang($msg));
        }
        $book_model->updateBook($arrData);
        $this->success(lang("EDIT_SUCCESS"), url("book/index"));

    }

    public function updateNotify($id,$has_notified)
    {
        $book_model = new CourseModel();
        $data = $book_model->getBookByID($id);
        $data['has_notified'] = !$has_notified;
        $book_model->updateBook($data);
        $this->success(lang("EDIT_SUCCESS"),url("book/index"));
    }

    /**
     * 删除预定
     * @adminMenu(
     *     'name'   => '删除预定',
     *     'parent' => 'index',
     *     'display'=> false,
     *     'hasView'=> false,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '删除预定',
     *     'param'  => ''
     * )
     */
    public function delete()
    {
        $book_model = new CourseModel();
        $id    = $this->request->param("id", 0, "intval");

        if (empty($id)) {
            $this->error(lang("NO_ID"));
        }

        $book_model->deleteBook($id);

        return $this->success(lang("DELETE_SUCCESS"), url("book/index"));

    }

    public function search()
    {
        $book_model = new CourseModel();
        //通过预定手机
        $phone = $this->request->param("book_phone","");
        $book_list = $book_model->getBookList();
        if ($phone != "")
        {
            $book_list = $book_model->getBooks($phone);
            if(empty($book_list))
                $book_list = new Collection();
        }
        //通过课程ID
        $cid = $this->request->param("cid","");
        if($cid != "") {
            $temp_list = $book_model->getBooksByCID($cid);
            $book_list = $this->removeRedundentBook($book_list, $temp_list);
        }
        //通过是否通知查找
        $book_has_notified = $this->request->param("book_has_notified");
        if ($book_has_notified != "请选择")
        {
            $temp_list = $book_model->getBooksByNotified($book_has_notified);
            if(!empty($temp_list))
                $book_list = $this->removeRedundentBook($book_list, $temp_list);
        }
        $this->assign('books', $book_list);
        $this->assign('phone', $phone);
        $this->assign('cid', $cid);
        $this->assign('book_has_notified', $book_has_notified);
        $book_has_notified = $book_has_notified == "请选择" ? -1 : $book_has_notified;
        $this->assign('option_html', $this->getOptionHtml($book_has_notified));
        return $this->fetch("book/index");
    }

    private function removeRedundentBook($cl1, $cl2)
    {

        $book_list = new Collection();
        foreach ($cl1 as $book1)
        {
            foreach ($cl2 as $book2)
            {
                if($book1['id'] == $book2['id'])
                {
                    $book_list->push($book1);
                }
            }
        }
        return $book_list;
    }

    private function getBookValidate()
    {
        //验证
        $rule = [
            'phone'  => 'require|number',
            'cid'   => 'require|number',
            'has_notified'   => 'require|number|between:0,1',
//            'time'  => 'require',
        ];

        $msg = [
            'phone.require' => '电话号码必须',
            'cid.require' => '课程ID必须',
            'has_notified.require' => '预定是否通知必须',
//            'time.require' => '时间必须',
            'phone.number'        => '电话号码只能是数字',
            'cid.number'        => '课程ID只能是数字',
            'has_notified.number'        => '预定是否通知只能是数字',
            'has_notified.between'  => '预定类型只能在0-1之间',
        ];
        return new Validate($rule, $msg);
    }

    private function getOptionHtml($has_notified=-1)
    {
        $option_html = "<option>请选择</option>";
        for($op = 0; $op <= 1; $op++)
        {
            if ($op == $has_notified)
                $option_html .= "<option selected=\"selected\">" . $op . "</option>";
            else
                $option_html .= "<option>" . $op . "</option>";
        }
        return $option_html;
    }

}