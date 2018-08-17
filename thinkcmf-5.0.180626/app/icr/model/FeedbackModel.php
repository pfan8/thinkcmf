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

class FeedbackModel extends Model
{

    /**
     * 添加反馈
     * @param $data
     * @return
     */
    public function insertFeedback($data)
    {
        $feedback = [
            'content' => $data['content'],
            'uid' => $data['uid'],
            'cid' => $data['cid'],
            'type' => $data['type'],
            'icon' => $data['icon'],
            'title' => $data['title'],
        ];
        Db::name('icr_feedback')->insert($feedback);
    }

    /**
     * 修改反馈
     * @param $data
     * @return
     */
    public function updateFeedback($data)
    {
        $fid =$data['id'];
        $result = Db::name('icr_feedback')
            ->where('id',$fid)
            ->find();
        if(empty($result))
        {
            echo "不存在";
            return;
        }
        Db::name('icr_feedback')
            ->where('id',$fid)
            ->update([
                    'content' => $data['content'],
                    'uid' => $data['uid'],
                    'cid' => $data['cid'],
                    'icon' => $data['icon'],
                    'title' => $data['title'],
                    'type' => $data['type'],]
                    );
    }

    /**
     * 删除反馈
     * @param $data
     * @return
     */
    public function deleteFeedback($id)
    {
        $result = Db::name('icr_feedback')
            ->where('id',$fid)
            ->find();
        if(empty($result))
        {
            echo "不存在";
            return;
        }
        Db::name('icr_feedback')->where('id',$id)->delete();
    }

    /**
     * 获取反馈列表，默认limit100
     * @param $limit
     * @return
     */
    public function getFeedbackList($limit=100)
    {
        return Db::name('icr_feedback')
            ->limit($limit)
            ->select();
    }

    /**
     * 通过id查询反馈
     * @param $data
     * @return
     */
    public function getFeedbackByID($id)
    {
        return  Db::name('icr_feedback')->where('id',$id)->find();
    }

    /**
     * 通过课程id查询反馈
     * @param $data
     * @return
     */
    public function getFeedbackByCID($cid)
    {
        return Db::name('icr_feedback')->where('cid',$cid)->select();
    }

    /**
     * 通过用户id查询反馈
     * @param $data
     * @return
     */
    public function getFeedbackByUID($uid)
    {
        return Db::name('icr_feedback')->where('uid',$uid)->select();
    }

    /**
     * 通过类别查询反馈
     * @param $type
     * @return
     */
    public function getFeedbackByType($type)
    {
        return Db::name('icr_feedback')->where('type',$type)->select();
    }

    public function transformContentToHtml(&$feedback)
    {
        $content_html = "";
        //文字视频不同处理
        if($feedback['type'] == 1)
        {
            foreach (explode("\n", $feedback['content']) as $item)
            {
                $content_html .= "<p>" . $item . "</p>";
                $content_html .= "\n";
            }
        } elseif ($feedback['type'] == 2)
        {
//            $content_html .= "<video src='" . $feedback['content'] ."' controls='controls' style='width: 200px;height: 200px'></video>";
        }
        $feedback['content'] = $content_html;
    }
}