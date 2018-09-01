<?php
/**
 * Created by PhpStorm.
 * User: pfan8
 * Date: 2018/8/2
 * Time: 19:07
 */

namespace app\icr\model;

use think\Db;
use think\Model;

class SchoolModel extends Model
{

    /**
     * 添加校区
     * @param $data
     * @return
     */
    public function insertSchool($data)
    {
        $school_existed = Db::name('icr_school')->where($data)->find();
        if(!empty($school_existed)){
            echo "已添加过！";
            return;
        }
        Db::name('icr_school')->insert($data);
    }

    /**
     * 添加图片
     * @param $data,需要有sid和图片的url
     * @return
     */
    public function insertPicture($data)
    {
        $picture = [
            'sid' => $data['sid'],
            'url' => $data['url'],
        ];
        Db::name('icr_picture')->insert($picture);
    }

    /**
     * 添加活动
     * @param $data,需要有sid
     * @return
     */
    public function insertActivity($data)
    {
        $activity = [
            'sid' => $data['sid'],
            'name' => $data['name'],
            'desc' => $data['desc'],
//            'start_time' => empty($data['start_time']) ? "" : $data['start_time'],
//            'end_time' => empty($data['end_time']) ? "" : $data['end_time'],
        ];
        Db::name('icr_activity')->insert($activity);
    }

    /**
     * 修改校区
     * @param $data
     * @return
     */
    public function updateSchool($data)
    {
        $sid =$data['id'];
        $result = Db::name('icr_school')
            ->where('id',$sid)
            ->find();
        if(empty($result))
        {
            echo "不存在";
            return;
        }
        Db::name('icr_school')
            ->where('id',$sid)
            ->update($data);
    }

    /**
     * 修改活动
     * @param $data
     * @return
     */
    public function updateActivity($data)
    {
        $aid =$data['id'];
        $result = Db::name('icr_activity')
            ->where('id',$aid)
            ->find();
        if(empty($result))
        {
            echo "不存在";
            return;
        }
        Db::name('icr_activity')
            ->where('id',$aid)
            ->update([
                    'sid' => $data['sid'],
                    'name' => $data['name'],
                    'desc' => $data['desc'],
                    'icon' => $data['icon'],
//                    'start_time' => empty($data['start_time']) ? "" : $data['start_time'],
//                    'end_time' => empty($data['end_time']) ? "" : $data['end_time'],
                ]);
    }

    /**
     * 删除校区
     * @param $id
     * @return
     */
    public function deleteSchool($id)
    {
        $result = Db::name('icr_school')
            ->where('id',$id)
            ->find();
        if(empty($result))
        {
            echo "不存在";
            return;
        }
        Db::name('icr_school')->where('id',$id)->delete();

        Db::name('icr_activity')
            ->where('sid',$id)
            ->delete();

        Db::name('icr_picture')
            ->where('sid',$id)
            ->delete();
    }

    /**
     * 删除图片
     * @param $id
     * @return
     */
    public function deletePicture($id)
    {
        $result = Db::name('icr_picture')
            ->where('id',$id)
            ->find();
        if(empty($result))
        {
            echo "不存在";
            return;
        }
        Db::name('icr_picture')->where('id',$id)->delete();
    }

    /**
     * 删除活动
     * @param $id
     * @return
     */
    public function deleteActivity($id)
    {
        $result = Db::name('icr_activity')
            ->where('id',$id)
            ->find();
        if(empty($result))
        {
            echo "不存在";
            return;
        }
        Db::name('icr_activity')->where('id',$id)->delete();
    }

    /**
     * 获取校区列表，默认limit100
     * @param $limit
     * @return
     */
    public function getSchoolList($limit=100)
    {
        return Db::name('icr_school')
            ->limit($limit)
            ->select();
    }

    /**
     * 获取活动列表，默认limit100
     * @param $limit
     * @return
     */
    public function getActivityList($limit=100)
    {
        return Db::name('icr_activity')
            ->limit($limit)
            ->select();
    }

    /**
     * 获取校区列表，默认limit100
     * @param $limit
     * @return
     */
    public function getPictureList($limit=100)
    {
        return Db::name('icr_picture')
            ->limit($limit)
            ->select();
    }

    /**
     * 通过id查询校区
     * @param $data
     * @return
     */
    public function getSchoolByID($id)
    {
        return Db::name('icr_school')->where('id',$id)->find();
    }
    /**
     * 查询city
     */
    public function getCityList(){
        return Db::name('icr_school')->distinct(true)->field(['city'])->select();
    }

    /**
     * 通过名称查询校区
     * @param $data
     * @return
     */
    public function getSchoolByName($name)
    {
        return Db::name('icr_school')->where('name','like',"%".$name."%")->select();
    }

    /**
     * 通过位置查询校区
     * @param $data
     * @return
     */
    public function getSchoolByLocation($location)
    {
        return Db::name('icr_school')->where('location','like',"%".$location."%")->select();
    }

    /**
     * 通过城市查询校区
     * @param $data
     * @return
     */
    public function getSchoolByCity($city)
    {
        return Db::name('icr_school')->where('city','like',"%".$city."%")->select();
    }

    /**
     * 通过id查询图片
     * @param $data
     * @return
     */
    public function getPictureByID($id)
    {
        return Db::name('icr_picture')->where('id',$id)->find();
    }

    /**
     * 通过Schoolid查询图片
     * @param $data
     * @return
     */
    public function getPictureBySchoolID($sid)
    {
        return Db::name('icr_picture')->where('sid',$sid)->find();
    }

    /**
     * 通过Schoolid查询图片list
     * @param $data
     * @return
     */
    public function getPictureListBySchoolID($sid)
    {
        return Db::name('icr_picture')->where('sid',$sid)->select();
    }

    /**
     * 通过id查询活动
     * @param $data
     * @return
     */
    public function getActivityByID($id)
    {
        return Db::name('icr_activity')->where('id',$id)->find();
    }

    /**
     * 通过id查询活动
     * @param $data
     * @return
     */
    public function getActivityBySchoolID($sid)
    {
        return Db::name('icr_activity')->where('sid',$sid)->select();
    }

    /**
     * 通过名称查询活动
     * @param $data
     * @return
     */
    public function getActivityByName($name)
    {
        Db::name('icr_activity')->where('name','like',"%".$name."%")->select();
    }

    /**
     * 通过内容查询活动
     * @param $data
     * @return
     */
    public function getActivityByDesc($desc)
    {
        return Db::name('icr_activity')->where('desc','like',"%".$desc."%")->select();
    }

    /**
     * 通过开始时间查询活动
     * @param $data
     * @return
     */
    public function getActivityByStartTime($start_time)
    {
        Db::name('icr_activity')->where('start_time',$start_time)->select();
    }

    /**
     * 通过截止时间查询活动
     * @param $data
     * @return
     */
    public function getActivityByEndTime($end_time)
    {
        return Db::name('icr_activity')->where('end_time',$end_time)->select();
    }

    /**
     * 查询指定开始时间之后的活动
     * @param $data
     * @return
     */
    public function getActivityAfterStartTime($start_time)
    {
        return Db::name('icr_activity')->where('start_time','egt',$start_time)->select();
    }

    /**
     * 查询指定截止时间之前的活动
     * @param $data
     * @return
     */
    public function getActivityBeforeEndTime($end_time)
    {
        return Db::name('icr_activity')->where('end_time','elt',$end_time)->select();
    }
}