<?php

/**
 * Created by PhpStorm.
 * User: pfan8
 * Date: 2018/7/30
 * Time: 22:40
 */
namespace app\icr\controller;

use app\icr\model\CourseModel;
use app\icr\model\SchoolModel;
use cmf\controller\HomeBaseController;

class SchoolController extends HomebaseController{

    // 首页
    public function index(){
        $data = $this->request->param();
        hook('switch_theme',$data);
        $head_controller = new HeadController();
        $head_controller->setHeaderActive("school");
        $school_model = new SchoolModel();
        $course_model = new CourseModel();
        $category_list = $course_model->getCategoryList();
        $city = session('city');
        if(!$city){
            $city = "厦门";
        }
        $school = $school_model->getSchoolByCity($city);
//        $this->complementSchool($school);
        $school_picture_list = $school_model->getPictureListBySchoolID($school[0]['id']);
//        $this->complementPicture($school_picture_list);
//        $this->complementActivity($school_activity);
        $school_picture = $this->transformPictureList($school_picture_list);
        $school_activity = $school_model->getActivityBySchoolID($school[0]['id']);
        $this->assign('school',$school);
        $this->assign('category_list',$category_list);
        $this->assign('school_picture',$school_picture);
        $this->assign('school_activity',$school_activity);
        return $this->fetch(':school');
    }

    public function getSchoolList() {
        $school_model = new SchoolModel();
        return $school_model->getSchoolList();
    }

    /**
     * 通过城市查询校区
     * @param $data
     * @return
     */
    public function getSchoolByCity() {
        $data = $this->request->post();
        $school_list = [];
        if (!empty($data['city'])) {
            $school_model = new SchoolModel();
            $school_list = $school_model->getSchoolByCity($data['city']);
        }
        return $school_list->toJson();
    }

    /**
     * 添加校区
     * @param $data
     * @return
     */
    public function insertSchool()
    {
        $name = $_GET['name'];
        $location = $_GET['location'];
        $city = $_GET['city'];

        $data = [
            'name' => $name,
            'location' => $location,
            'city' => $city,
        ];

        $school_model = new SchoolModel();
        $school_model->insertSchool($data);
    }

    /**
     * 添加图片
     * @param
     * @return
     */
    public function insertPicture()
    {
        $sid = $_GET['sid'];
        $url = $_GET['url'];

        $data = [
            'sid' => $sid,
            'url' => $url,
        ];

        $school_model = new SchoolModel();
        $school_model->insertPicture($data);
    }

    /**
     * 添加活动
     * @param
     * @return
     */
    public function insertActivity()
    {
        $sid = $_GET['sid'];
        $name = $_GET['name'];
        $desc = $_GET['desc'];
        $start_time = $_GET['start_time'];
        $end_time = $_GET['end_time'];

        $data = [
            'sid' => $sid,
            'name' => $name,
            'desc' => $desc,
            'start_time' => $start_time,
            'end_time' => $end_time,
        ];

        $school_model = new SchoolModel();
        $school_model->insertActivity($data);
    }

    /**
     * 修改校区
     * @param $data
     * @return
     */
    public function updateSchool()
    {
        $name = $_GET['content'];
        $location = $_GET['location'];
        $city = $_GET['city'];

        $data = [
            'id' => $_GET['id'],
            'name' => $name,
            'location' => $location,
            'city' => $city,
        ];

        $school_model = new SchoolModel();
        $school_model->updateSchool($data);
    }

    /**
     * 修改活动
     * @para $data
     * @return
     */
    public function updateActivity()
    {
        $id = $_GET['id'];
        $name = $_GET['name'];
        $desc = $_GET['desc'];
        $icon = $_GET['icon'];
        $start_time = $_GET['start_time'];
        $end_time = $_GET['end_time'];

        $data = [
            'id' => $id,
            'name' => $name,
            'desc' => $desc,
            'icon' => $icon,
            'start_time' => $start_time,
            'end_time' => $end_time,
        ];

        $school_model = new SchoolModel();
        $school_model->updateActivity($data);
    }

    /**
     * 删除校区
     * @param $data
     * @return
     */
    public function deleteSchool()
    {
        $school_model = new SchoolModel();
        $school_model->deleteSchool($_GET['id']);
    }

    /**
     * 删除图片
     * @param $data
     * @return
     */
    public function deletePicture()
    {
        $school_model = new SchoolModel();
        $school_model->deletePicture($_GET['id']);
    }

    /**
     * 删除活动
     * @param $data
     * @return
     */
    public function deleteActivity()
    {
        $school_model = new SchoolModel();
        $school_model->deleteActivity($_GET['id']);
    }

    /**
     * 通过id查询校区
     * @param $data
     * @return
     */
    public function getSchoolByID()
    {
        $school_model = new SchoolModel();
        return $school_model->getSchoolByID($_GET['id']);
    }

    /**
     * 通过名称查询校区
     * @param $data
     * @return
     */
    public function getSchoolByName()
    {
        $school_model = new SchoolModel();
        return $school_model->getSchoolByName($_GET['name']);
    }

    /**
     * 通过位置查询校区
     * @param $data
     * @return
     */
    public function getSchoolByLocation()
    {
        $school_model = new SchoolModel();
        return $school_model->getSchoolByLocation($_GET['location']);
    }


    /**
     * 通过id查询图片
     * @param $data
     * @return
     */
    public function getPictureByID()
    {
        $school_model = new SchoolModel();
        return $school_model->getPictureByID($_GET['id']);
    }

    /**
     * 通过id查询活动
     * @param $data
     * @return
     */
    public function getActivityByID()
    {
        $school_model = new SchoolModel();
        return $school_model->getActivityByID($_GET['id']);
    }

    /**
     * 通过名称查询活动
     * @param $data
     * @return
     */
    public function getActivityByName()
    {
        $school_model = new SchoolModel();
        return $school_model->getActivityByName($_GET['name']);
    }

    /**
     * 通过内容查询活动
     * @param $data
     * @return
     */
    public function getActivityByDesc()
    {
        $school_model = new SchoolModel();
        return $school_model->getActivityByDesc($_GET['desc']);
    }

    /**
     * 通过开始时间查询活动
     * @param $data
     * @return
     */
    public function getActivityByStartTime()
    {
        $school_model = new SchoolModel();
        return $school_model->getActivityByStartTime($_GET['start_time']);
    }

    /**
     * 通过截止时间查询活动
     * @param $data
     * @return
     */
    public function getActivityByEndTime()
    {
        $school_model = new SchoolModel();
        return $school_model->getActivityByEndTime($_GET['end_time']);
    }

    /**
     * 查询指定开始时间之后的活动
     * @param $data
     * @return
     */
    public function getActivityAfterStartTime()
    {
        $school_model = new SchoolModel();
        return $school_model->getActivityAfterStartTime($_GET['start_time']);
    }

    /**
     * 查询指定截止时间之前的活动
     * @param $data
     * @return
     */
    public function getActivityBeforeEndTime()
    {
        $school_model = new SchoolModel();
        return $school_model->getActivityBeforeEndTime($_GET['end_time']);
    }

    /**
     * 转换图片输出到html
     * @param $picture_list
     * @return
     */
    public function transformPictureList($picture_list)
    {
        $picture = "";
        foreach ($picture_list as $item)
        {
            $picture .= "<div class=\"path-img\"><img  src=\"";
            $picture .= $item['url'];
            $picture .= "\"/></div>\n";
        }
        return $picture;
    }

    private function complementActivity(&$activitys)
    {
        for ($i = 0; $i < count($activitys); $i++) {
            $activity = $activitys->shift();
            if (empty($activity['icon'])) {
                $activity['icon'] = '/themes/RY/icr/imgs/timg.jpg';
            }
            $activitys->push($activity);
        }
        while (count($activitys) < 6) {
            $activity = [
                'icon' => '/themes/RY/icr/imgs/timg.jpg',
                'name' => '待添加',
            ];
            $activitys->push($activity);
        }
    }

    private function complementSchool(&$schools)
    {
        for ($i = 0; $i < count($schools); $i++) {
            $school = $schools->shift();
            if (empty($school['icon'])) {
                $school['icon'] = '/themes/RY/icr/imgs/timg.jpg';
            }
            $schools->push($school);
        }
        while (count($schools) < 6) {
            $school = [
                'icon' => '/themes/RY/icr/imgs/timg.jpg',
                'name' => '待添加',
            ];
            $schools->push($school);
        }
    }

    private function complementPicture(&$pictures)
    {
        for ($i = 0; $i < count($pictures); $i++) {
            $picture = $pictures->shift();
            if (empty($picture['icon'])) {
                $picture['icon'] = '/themes/RY/icr/imgs/timg.jpg';
            }
            $pictures->push($picture);
        }
        while (count($pictures) < 6) {
            $picture = [
                'icon' => '/themes/RY/icr/imgs/timg.jpg',
                'name' => '待添加',
            ];
            $pictures->push($picture);
        }
    }

}