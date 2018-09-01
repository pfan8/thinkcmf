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

class RecruitModel extends Model
{

    /**
     * 添加招聘
     * @param $data
     * @return
     */
    public function insertRecruit($data)
    {
        $recruit = [
            'position' => $data['position'],
            'desc' => $data['desc'],
            'require' => $data['require'],
            'start_time' => empty($data['start_time']) ? "" : $data['start_time'],
            'end_time' => empty($data['end_time']) ? "" : $data['end_time'],
        ];
        $recruit_existed = Db::name('icr_recruit')->where($recruit)->find();
        if(!empty($recruit_existed)){
            echo "已添加过！";
            return;
        }
        Db::name('icr_recruit')->insert($recruit);
    }

    /**
     * 修改招聘
     * @param $data
     * @return
     */
    public function updateRecruit($data)
    {
        $rid =$data['id'];
        $result = Db::name('icr_feedback')
            ->where('id',$rid)
            ->find();
        if(empty($result))
        {
            echo "不存在";
            return;
        }
        Db::name('icr_recruit')
            ->where('id',$rid)
            ->update($data);
    }

    /**
     * 删除招聘
     * @param $data
     * @return
     */
    public function deleteRecruit($id)
    {
        $result = Db::name('icr_recruit')
            ->where('id',$id)
            ->find();
        if(empty($result))
        {
            echo "不存在";
            return;
        }
        Db::name('icr_recruit')->where('id',$id)->delete();
    }

    /**
     * 获取招聘列表，默认limit100
     * @param $limit
     * @return
     */
    public function getRecruitList($limit=100)
    {
        return Db::name('icr_recruit')
            ->limit($limit)
            ->select();
    }

    /**
     * 通过id查询招聘
     * @param $data
     * @return
     */
    public function getRecruitByID($id)
    {
        return Db::name('icr_recruit')->where('id',$id)->find();
    }

    /**
     * 通过职位查询招聘
     * @param $data
     * @return
     */
    public function getRecruitByPosition($position)
    {
        return Db::name('icr_recruit')->where('position','like',"%".$position."%")->select();
    }

    /**
     * 通过描述查询招聘
     * @param $data
     * @return
     */
    public function getRecruitByDesc($desc)
    {
        return Db::name('icr_recruit')->where('desc','like',"%".$desc."%")->select();
    }

    /**
     * 通过要求查询招聘
     * @param $data
     * @return
     */
    public function getRecruitByRequire($require)
    {
        return Db::name('icr_recruit')->where('require','like',"%".$require."%")->select();
    }

    /**
     * 通过开始时间查询招聘
     * @param $data
     * @return
     */
    public function getRecruitByStartTime($start_time)
    {
        return Db::name('icr_recruit')->where('start_time',$start_time)->select();
    }

    /**
     * 通过截止时间查询招聘
     * @param $data
     * @return
     */
    public function getRecruitByEndTime($end_time)
    {
        return Db::name('icr_recruit')->where('end_time',$end_time)->select();
    }

    /**
     * 查询指定开始时间之后的招聘
     * @param $data
     * @return
     */
    public function getRecruitAfterStartTime($start_time)
    {
        return Db::name('icr_recruit')->where('start_time','egt',$start_time)->select();
    }

    /**
     * 查询指定截止时间之前的招聘
     * @param $data
     * @return
     */
    public function getRecruitBeforeEndTime($end_time)
    {
        return Db::name('icr_recruit')->where('end_time','elt',$end_time)->select();
    }
}