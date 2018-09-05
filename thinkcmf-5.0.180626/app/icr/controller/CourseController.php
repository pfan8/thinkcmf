<?php

/**
 * Created by PhpStorm.
 * User: pfan8
 * Date: 2018/7/30
 * Time: 22:40
 */
namespace app\icr\controller;

use app\icr\model\CourseModel;
use app\icr\model\FeedbackModel;
use cmf\controller\HomeBaseController;
use think\Validate;

class CourseController extends HomebaseController{

    // 首页
    public function index(){
        $data = $this->request->param();
        hook('switch_theme',$data);
        $head_controller = new HeadController();
        $head_controller->setHeaderActive("course");
        $course_model = new CourseModel();
        $param = $this->request->param();
        $s_category = empty($param['s_category']) ? 1: $param['s_category'];
        if ($s_category != 1){
            if (count($course_model->getCourseLevelsByCategoryID($s_category)) == 0)
                $s_level = 0;
            else
                $s_level = $course_model->getCourseLevelsByCategoryID($s_category)[0]['id'];
        } else if (!empty($param['s_level'])) {
            $s_level = $param['s_level'];
            $s_category = $course_model->getCourseLevelByID($s_level)['category_id'];
        } else {
            $s_level = $course_model->getCourseLevelsByCategoryID($s_category)[0]['id'];
        }
        $level_class = $this->getSearchLevel($s_category);
        $course = $course_model->getCourseByLevel($s_level);
        $feedback_model = new FeedbackModel();
        $feedback_video = $feedback_model->getFeedbackByType(2);
        $feedback_text = $feedback_model->getFeedbackByType(1);
        for ($i = 0;$i < count($feedback_video); $i++) {
            $feedback = $feedback_video->shift();
            $feedback_model->transformContentToHtml($feedback);
            $feedback_video->push($feedback);
        }
        for ($i = 0;$i < count($feedback_text); $i++) {
            $feedback = $feedback_text->shift();
            $feedback_model->transformContentToHtml($feedback);
            $feedback_text->push($feedback);
        }
        $levels = $course_model->getCourseLevelsByCategoryID($s_category);
        $goal_array = [];
        $goal_len = 0;
        $category = $course_model->getCategoryByID($s_category);
        $level = $course_model->getCourseLevelByID($s_level);
        if (!empty($level['goal'])) {
            $goal_array = explode("\n", $level['goal']);
            $goal_len = count($goal_array);
        }
        if($goal_len < 6)
        {
            for ($goal_len; $goal_len <= 6;$goal_len++)
            {
                $goal_array[] = "";
            }
        }
//        $this->complementCourse($course);
//        $this->complementFeedback($feedback_video);
//        $this->complementFeedback($feedback_text);
        $this->assign('levels',$levels);
        $this->assign('level_class', $level_class);
        $this->assign('s_level',$s_level);
        $this->assign('s_category',$s_category);
        $this->assign('course', $course);
        $this->assign('feedback_video', $feedback_video);
        $this->assign('feedback_text', $feedback_text);
        $this->assign('goal_array', $goal_array);
        return $this->fetch(':course');
    }

    public function getBookCourseCategoryList() {
        $course_model = new CourseModel();
        $category_list = $course_model->getCategoryList();
        $result = $category_list;
        $this->result($result,0,'success','json');
    }

    /**
     * 添加课程
     * @param $data
     * @return
     */
    public function insertCourse() {
        $name = $_GET['name'];
        $describe = $_GET['describe'];
        $level = $_GET['level'];
        $type = $_GET['type'];
        $teacher_id = $_GET['teacher_id'];

        $data = [
            'name' => $name,
            'describe' => $describe,
            'level' => $level,
            'type' => $type,
            'teacher_id' => $teacher_id,
        ];

        //验证
        $rule = [
            'name'  => 'require',
            'level'   => 'require|number|between:1,6',
        ];

        $msg = [
            'name.require' => '课程名必须',
            'level.require'   => '课程等级必须',
            'level.between'  => '课程等级只能在1-6之间',
            'level.number'        => '课程等级只能是数字',
        ];
        $validate = new Validate($rule, $msg);
        if(!$validate->check($data)){
            dump($validate->getError());
            return;
        }

        $course_model = new CourseModel();
        $course_model->insertCourse($data);
    }

    /**
     * 修改课程
     * @param $data
     * @return
     */
    public function updateCourse() {
        $course_model = new CourseModel();

        $name = $_GET['name'];
        if ($_SESSION['id'])
            $id = $_GET['id'];
        else
            $id = $course_model->getCourseID($name);
        $describe = $_GET['describe'];
        $level = $_GET['level'];
        $type = $_GET['type'];
        $icon = $_GET['icon'];
        $teacher_id = $_GET['teacher_id'];

        $data = [
            'id' => $id,
            'name' => $name,
            'describe' => $describe,
            'level' => $level,
            'type' => $type,
            'icon' => $icon,
            'teacher_id' => $teacher_id,
        ];

        //验证
        $rule = [
            'name'  => 'require',
            'type' => 'require',
            'level'   => 'require|number|between:1,6',
        ];

        $msg = [
            'name.require' => '课程名必须',
            'type.require' => '课程类别必须',
            'level.require'   => '课程等级必须',
            'level.between'  => '课程等级只能在1-6之间',
            'level.number'        => '课程等级只能是数字',
        ];
        $validate = new Validate($rule, $msg);
        if(!$validate->check($data)){
            dump($validate->getError());
            return;
        }

        $course_model->updateCourse($data);
    }

    /**
     * 添加课程类别
     * @param $data
     * @return
     */
    public function insertCourseType(){

        $data = [
            'type' => $_GET['type'],
            'content' => $_GET['content'],
        ];

        //验证
        $rule = [
            'type' => 'require',
        ];

        $msg = [
            'type.require' => '课程类别必须',
        ];

        $validate = new Validate($rule, $msg);
        if(!$validate->check($data)){
            dump($validate->getError());
            return;
        }

        $course_model = new CourseModel();
        $course_model->insertCourseType($data);
    }

    /**
     * 预定课程
     * @param $data
     * @return
     */
    public function bookCourse(){

        $data = [
            'cid' => 1,
            'phone' => $_GET['phone'],
            'has_notified' => false,
            'time' => date("Y-m-d H:i:s"),
        ];

        //验证
        $rule = [
            'cid' => 'require|number',
            'phone' => 'require|number',
            'has_notified' => 'require',
            'time' => 'require',
        ];

        $msg = [
            'cid.require' => '课程id必须',
            'phone.require' => '电话号码必须',
            'has_notified.require' => '是否通知必须',
            'time.require' => '预定时间必须',
        ];

        $validate = new Validate($rule, $msg);
        if(!$validate->check($data)){
            dump($validate->getError());
            return;
        }

        $course_model = new CourseModel();
        $course_model->bookCourse($data);
    }

    /**
     * 预定已提醒
     * @param
     * @return
     */
    public function notifyBook()
    {
        $id = $_GET['id'];
        $course_model = new CourseModel();
        return $course_model->notifyBook($id);
    }

    /**
     * 删除课程
     * @param
     * @return
     */
    public function deleteCourse()
    {
        $id = $_GET['id'];
        $course_model = new CourseModel();
        return $course_model->deleteCourse($id);
    }

    /**
     * 删除课程类别
     * @param
     * @return
     */
    public function deleteCourseType()
    {
        $id = $_GET['id'];
        $course_model = new CourseModel();
        return $course_model->deleteCourseType($id);
    }

    /**
     * 取消预定
     * @param
     * @return
     */
    public function deleteBook()
    {
        $id = $_GET['id'];
        $course_model = new CourseModel();
        return $course_model->deleteBook($id);
    }

    /**
     * 通过课程id查询课程
     * @param $data
     * @return
     */
    public function getCourseByID()
    {
        $cid = $_GET['id'];
        $course_model = new CourseModel();
        return $course_model->getCourseByID($cid);
    }

    /**
     * 通过课程名查询课程
     * @param $data
     * @return
     */
    public function getCourseByName()
    {
        $name = $_GET['name'];
        $course_model = new CourseModel();
        return $course_model->getCourseByName($name);
    }

    /**
     * 通过课程等级查询课程
     * @param $data
     * @return
     */
    public function getCourseByLevel($level = 1)
    {
        $course_model = new CourseModel();
        return $course_model->getCourseByLevel($level);
    }

    /**
     * 通过课程类别查询课程
     * @param $data
     * @return
     */
    public function getCourseByType()
    {
        $type = $_GET['type'];
        $course_model = new CourseModel();
        return $course_model->getCourseByType($type);
    }

    /**
     * 通过教师查询课程
     * @param $data
     * @return
     */
    public function getCourseByTeacher()
    {
        $tid = $_GET['teacher_id'];
        $course_model = new CourseModel();
        return $course_model->getCourseByTeacher($tid);
    }

    /**
     * 通过手机号查询订单
     * @param $data
     * @return
     */
    public function getBooks()
    {
        $phone = $_GET['phone'];
        $course_model = new CourseModel();
        return $course_model->getBooks($phone);
    }

    /**
     * 查询指定日期之前的订单
     * @param $data
     * @return
     */
    public function getBooksBeforeTime()
    {
        $time = $_GET['time'];
        $course_model = new CourseModel();
        return $course_model->getBooksBeforeTime($time);
    }

    /**
     * 查询指定日期之后的订单
     * @param $data
     * @return
     */
    public function getBooksAfterTime()
    {
        $time = $_GET['time'];
        $course_model = new CourseModel();
        return $course_model->getBooksAfterTime($time);
    }

//    private function complementCourse(&$courses)
//    {
//        for ($i = 0; $i < count($courses); $i++) {
//            $course = $courses->shift();
//            if (empty($feedback['icon'])) {
//                $feedback['icon'] = '/themes/RY/icr/imgs/timg.jpg';
//            }
//            $courses->push($course);
//        }
//        while (count($courses) < 6) {
//            $course = [
//                'icon' => '/themes/RY/icr/imgs/timg.jpg',
//                'name' => '待添加',
//            ];
//            $courses->push($course);
//        }
//    }
//
//    private function complementFeedback(&$feedbacks)
//    {
//        for ($i = 0; $i < count($feedbacks); $i++) {
//            $feedback = $feedbacks->shift();
//            if (empty($feedback['icon'])) {
//                $feedback['icon'] = '/themes/RY/icr/imgs/timg.jpg';
//            }
//            $feedbacks->push($feedback);
//        }
//
//        while (count($feedbacks) < 4) {
//            $feedback = [
//                'icon' => '/themes/RY/icr/imgs/timg.jpg',
//                'title' => '待添加',
//                'content' => '待添加',
//            ];
//            $feedbacks->push($feedback);
//        }
//    }

    private function getCourseLimitFromPage($page)
    {
        switch ($page) {
            case 1:
                return "0,6";
            case 2:
                return "6,12";
            case 4:
                return "12,18";
            case 5:
                return "18,24";
            default:
                return 100;
        }
    }

    private function getSearchLevel($s_level)
    {
        $level_class = [
            "tab-item",
            "tab-item",
            "tab-item",
        ];
        switch ($s_level)
        {
            case 1:
                $level_class[0] = "tab-item active";
                break;
            case 2:
                $level_class[1] = "tab-item active";
                break;
            case 3:
                $level_class[2] = "tab-item active";
                break;
            default:
                break;
        }
        return $level_class;
    }
}