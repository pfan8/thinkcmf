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
use app\icr\model\FeedbackModel;
use cmf\controller\AdminBaseController;
use think\Collection;
use think\Validate;

/**
 * Class NavController 反馈类别管理控制器
 * @package app\admin\controller
 */
class FeedbackController extends AdminBaseController
{
    /**
     * 反馈管理
     * @adminMenu(
     *     'name'   => '反馈管理',
     *     'parent' => 'admin/Setting/default',
     *     'display'=> true,
     *     'hasView'=> true,
     *     'order'  => 30,
     *     'icon'   => '',
     *     'remark' => '反馈管理',
     *     'param'  => ''
     * )
     */
    public function index()
    {
        $feedbackModel = new FeedbackModel();

        $feedbacks = $feedbackModel->getFeedbackList();
        $this->transformToHtml($feedbacks);
        $this->assign('feedbacks', $feedbacks);
        $this->assign('cid','');
        $this->assign('uid','');
        $this->assign('option_html',$this->getOptionHtml());

        return $this->fetch();

    }

    /**
     * 添加反馈
     * @adminMenu(
     *     'name'   => '添加反馈',
     *     'parent' => 'index',
     *     'display'=> false,
     *     'hasView'=> true,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '添加反馈',
     *     'param'  => ''
     * )
     */
    public function add()
    {
        $this->assign('option_html',$this->getOptionHtml());
        return $this->fetch();
    }

    /**
     * 添加反馈提交保存
     * @adminMenu(
     *     'name'   => '添加反馈提交保存',
     *     'parent' => 'index',
     *     'display'=> false,
     *     'hasView'=> false,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '添加反馈提交保存',
     *     'param'  => ''
     * )
     */
    public function addPost()
    {
        $data = $this->request->post();
        if($data['type'] == 2) {
            $file=$this->request->file('content');
            if($file){
                $path = ROOT_PATH . 'public' . DS . 'upload' . DS . "video";
                // 文件大小限制50M
                $max_file_size = 50*1024*1024;
                $info = $file->validate(['size'=>$max_file_size,'ext'=>'mp4'])->move($path);
                if($info){
                    $data['content'] = DS.'upload'.DS.'video'.DS.$info->getSaveName();
                }else{
                    // 上传失败获取错误信息
                    return $this->error(lang($file->getError()), url('feedback/add'));
                }
            } else {
                return $this->error(lang("获取文件失败"), url('feedback/add'));
            }
        }
        $validate = $this->getFeedbackValidate();
        if(!$validate->check($data)){
            $msg = $validate->getError();
            $this->error(lang($msg), url('feedback/add'));
        }
        $feedback_model = new FeedbackModel();
        $course_model = new CourseModel();
        $user_model = new UserModel();
        $is_course_exist = $course_model->getCourseByID($data['cid']);
        if(!$is_course_exist)
            return $this->error(lang("没有该课程"), url('feedback/add'));
        $is_user_exist = $user_model->where('id',$data['uid'])->find();
        if(empty($is_user_exist))
            return $this->error(lang("没有该用户"), url('feedback/add'));
        $feedback_model->insertFeedback($data);
        $this->success(lang('ADD_SUCCESS'), url('feedback/index'));
    }

    /**
     * 编辑反馈
     * @adminMenu(
     *     'name'   => '编辑反馈',
     *     'parent' => 'index',
     *     'display'=> false,
     *     'hasView'=> true,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '编辑反馈',
     *     'param'  => ''
     * )
     */
    public function edit()
    {
        $feedback_model = new FeedbackModel();
        $id    = $this->request->param("id", 0, 'intval');

        $feedback = $feedback_model->getFeedbackByID($id);
        $arrNavCat = $feedback ? $feedback : [];
        //前端默认选择反馈等级
        $option_html = $this->getOptionHtml($feedback['type']);
        $this->assign('option_html',$option_html);
        $this->assign($arrNavCat);
        return $this->fetch();
    }


    /**
     * 编辑反馈提交保存
     * @adminMenu(
     *     'name'   => '编辑反馈提交保存',
     *     'parent' => 'index',
     *     'display'=> false,
     *     'hasView'=> false,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '编辑反馈提交保存',
     *     'param'  => ''
     * )
     */
    public function editPost()
    {
        $data = $this->request->post();
        $file=$this->request->file('content');
        if($file){
            $path = ROOT_PATH . 'public' . DS . 'upload' . DS . "video";
            // 文件大小限制500M
            $max_file_size = 500*1024*1024;
            $info = $file->validate(['size'=>$max_file_size,'ext'=>'mp4'])->move($path);
            if($info){
                $data['content'] = DS.'upload'.DS.'video'.DS.$info->getSaveName();
            }else{
                // 上传失败获取错误信息
                return $this->error(lang($file->getError()), url('feedback/add'));
            }
        } else {
            return $this->error("获取文件失败", url('feedback/add'));
        }

        $feedback_model = new FeedbackModel();
        $course_model = new CourseModel();
        $user_model = new UserModel();
        $is_course_exist = $course_model->getCourseByID($data['cid']);
        if(!$is_course_exist)
            return $this->error(lang("没有该课程"), url('feedback/add'));
        $is_user_exist = $user_model->where('id',$data['uid'])->find();
        if(empty($is_user_exist))
            return $this->error(lang("没有该用户"), url('feedback/add'));


        $validate = $this->getFeedbackValidate();
        if(!$validate->check($data)){
            $msg = $validate->getError();
            $this->error(lang($msg), url('feedback/edit',array('id' => $data['id'])));
        }
        $feedback_model->updateFeedback($data);
        $this->success(lang("EDIT_SUCCESS"), url("feedback/index"));

    }

    /**
     * 删除反馈
     * @adminMenu(
     *     'name'   => '删除反馈',
     *     'parent' => 'index',
     *     'display'=> false,
     *     'hasView'=> false,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '删除反馈',
     *     'param'  => ''
     * )
     */
    public function delete()
    {
        $feedback_model = new FeedbackModel();
        $id    = $this->request->param("id", 0, "intval");

        if (empty($id)) {
            $this->error(lang("NO_ID"));
        }

        $feedback_model->deleteFeedback($id);

        return $this->success(lang("DELETE_SUCCESS"), url("feedback/index"));

    }

    public function search()
    {
        $feedback_model = new FeedbackModel();
        //通过用户ID
        $uid = $this->request->param("uid","");
        $feedback_list = $feedback_model->getFeedbackList();
        if ($uid != "")
        {
            $feedback_list = $feedback_model->getFeedbackByUID($uid);
            if(empty($feedback_list))
                $feedback_list = new Collection();
        }
        //通过课程ID
        $cid = $this->request->param("cid","");
        if($cid != "") {
            $temp_list = $feedback_model->getFeedbackByCID($cid);
            $feedback_list = $this->removeRedundentFeedback($feedback_list, $temp_list);
        }
        //通过反馈类型查找
        $feedback_type = $this->request->param("feedback_type");
        if ($feedback_type != "请选择")
        {
            $temp_list = $feedback_model->getFeedbackByType($feedback_type);
            if(!empty($temp_list))
//                $feedback_list->intersect($temp_list);
                $feedback_list = $this->removeRedundentFeedback($feedback_list, $temp_list);
        }
        $this->transformToHtml($feedback_list);
        $this->assign('feedbacks', $feedback_list);
        $this->assign('uid', $uid);
        $this->assign('cid', $cid);
        $this->assign('feedback_type', $feedback_type);
        $this->assign('option_html', $this->getOptionHtml($feedback_type));
        return $this->fetch("feedback/index");
    }

    private function removeRedundentFeedback($cl1, $cl2)
    {

        $feedback_list = new Collection();
        foreach ($cl1 as $feedback1)
        {
            foreach ($cl2 as $feedback2)
            {
                if($feedback1['id'] == $feedback2['id'])
                {
                    $feedback_list->push($feedback1);
                }
            }
        }
        return $feedback_list;
    }

    private function getFeedbackValidate()
    {
        //验证
        $rule = [
            'uid'  => 'require|number',
            'cid'   => 'require|number',
            'type'   => 'require|number|between:1,2',
        ];

        $msg = [
            'uid.require' => '用户ID必须',
            'cid.require' => '课程ID必须',
            'type.require' => '反馈类型必须',
            'uid.number'        => '用户ID只能是数字',
            'cid.number'        => '课程ID只能是数字',
            'type.number'        => '反馈类型只能是数字',
            'type.between'  => '反馈类型只能在1-2之间',
        ];
        return new Validate($rule, $msg);
    }

    private function getOptionHtml($level=0)
    {
        $option_html = "<option>请选择</option>";
        for($op = 1; $op <= 2; $op++)
        {
            if ($op == $level)
                $option_html .= "<option selected=\"selected\">" . $op . "</option>";
            else
                $option_html .= "<option>" . $op . "</option>";
        }
        return $option_html;
    }

    private function transformToHtml(&$feedbacks)
    {
        foreach ($feedbacks as $item)
        {
            $feedbacks->shift();
            $feedback_model = new FeedbackModel();
            $feedback_model->transformContentToHtml($item);
            $feedbacks->push($item);
        }
    }

}