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

class TeacherModel extends Model
{

    /**
     * 获取教师列表，默认limit100
     * @param $limit
     * @return
     */
    public function getTeacherList($limit=100)
    {
        $limits = explode(',',$limit);
        if (!count($limits)) {
            return Db::name('icr_teacher')
                ->limit(intval($limits[0]),intval($limits[1]))
                ->select();
        } else {
            return Db::name('icr_teacher')
                ->limit($limit)
                ->select();
        }
    }

    /**
     * 添加教师
     * @param $data
     * @return
     */
    public function insertTeacher($data)
    {
        $teacher = [
            'name' => $data['name'],
            'position' => $data['position'],
            'resume' => $data['resume'],
            'phone' => $data['phone'],
            'gender' => $data['gender'],
            'age' => $data['age'],
            'icon' => $data['icon'],
            'idea' => $data['idea']
        ];
        $teacher_existed = Db::name('icr_teacher')->where($teacher)->find();
        if(!empty($teacher_existed)){
            echo "已添加过！";
            return $this->error;
        }
        return Db::name('icr_teacher')->insertGetId($teacher);
    }

    /**
     * 为教师添加课程
     * @param $tid,$cid
     * @return
     */
    public function addCourseToTeacher($tid, $cid)
    {
        if (Db::name('icr_teacher')->where("id",$tid)->find())
        {
            if (Db::name('icr_course')->where("id",$cid)->find())
            {
                $inter_data = [
                    'cid' => $cid,
                    'tid' => $tid,
                ];
                if (Db::name('icr_cteacher_intersect')->where($inter_data)->find())
                {
                    echo "已添加";
                    return $this->error;
                }
                Db::name('icr_cteacher_intersect')->insert($inter_data);
            } else {
                echo "课程不存在";
                return $this->error;
            }
        } else {
            echo "教师不存在";
            return $this->error;
        }
    }

    /**
     * 为教师删除课程
     * @param $tid,$cid
     * @return
     */
    public function deleteCourses($tid)
    {
        $inter_list = Db::name('icr_cteacher_intersect')->where("tid",$tid)->select();
        if (!empty($inter_list))
        {
            foreach ($inter_list as $inter_data)
                Db::name('icr_cteacher_intersect')->delete($inter_data);
        }
    }

    /**
     * 修改教师
     * @param $data
     * @return
     */
    public function updateTeacher($data)
    {
        $tid =$data['id'];
        $result = Db::name('icr_teacher')
            ->where('id',$tid)
            ->find();
        if(empty($result))
        {
            echo "不存在";
            return;
        }
        Db::name('icr_teacher')
            ->where('id',$tid)
            ->update([
                    'name' => $data['name'],
                    'position' => $data['position'],
                    'resume' => $data['resume'],
                    'phone' => $data['phone'],
                    'gender' => $data['gender'],
                    'age' => $data['age'],
                    'icon' => $data['icon'],
                    'idea' => $data['idea']]
            );
    }

    /**
     * 删除教师
     * @param $data
     * @return
     */
    public function deleteTeacher($id)
    {
        $result = Db::name('icr_teacher')
            ->where('id',$id)
            ->find();
        if(empty($result))
        {
            echo "不存在";
            return;
        }
        Db::name('icr_teacher')->where('id',$id)->delete();
    }

    /**
     * 获取课程列表
     * @param $data
     * @return
     */
    public function getCourseByTeacher($tid)
    {
        $course_model = new CourseModel();
        return $course_model->getCourseByTeacher($tid);
    }

    /**
     * 通过id查询教师
     * @param $data
     * @return
     */
    public function getTeacherByID($id)
    {
        return Db::name('icr_teacher')->where('id',$id)->find();
    }

    /**
     * 通过姓名查询教师
     * @param $data
     * @return
     */
    public function getTeacherByName($name)
    {
        return Db::name('icr_teacher')->where('name','like','%'.$name.'%')->select();
    }

    /**
     * 通过职位查询教师
     * @param $data
     * @return
     */
    public function getTeacherByPosition($position)
    {
        return Db::name('icr_teacher')->where('position',$position)->select();
    }

    /**
     * 通过简历查询教师
     * @param $data
     * @return
     */
    public function getTeacherByResume($resume)
    {
        return Db::name('icr_teacher')->where('resume','like',$resume)->select();
    }

    /**
     * 通过电话查询教师
     * @param $data
     * @return
     */
    public function getTeacherByPhone($phone)
    {
        return Db::name('icr_teacher')->where('phone',$phone)->select();
    }

    /**
     * 通过性别查询教师
     * @param $data
     * @return
     */
    public function getTeacherByGender($gender)
    {
        return Db::name('icr_teacher')->where('gender',$gender)->select();
    }

    /**
     * 通过年龄查询教师
     * @param $data
     * @return
     */
    public function getTeacherByAge($age)
    {
        return Db::name('icr_teacher')->where('age',$age)->select();
    }

    /**
     * 查询不大于指定年龄教师
     * @param $data
     * @return
     */
    public function getTeacherELTAge($age)
    {
        return Db::name('icr_teacher')->where('age','elt',$age)->select();
    }

    /**
     * 查询不小于指定年龄教师
     * @param $data
     * @return
     */
    public function getTeacherEGTAge($age)
    {
        return Db::name('icr_teacher')->where('age','egt',$age)->select();
    }

    /**
     * 通过课程id查询教师
     * @param $cid
     * @return
     */
    public function getTeacherByCourse($cid)
    {
        $tid = Db::name('icr_cteacher_intersect')->where('cid',$cid)->select();
        if(empty($tid['tid'])) {
            $teacher_list = [];
            foreach ($tid as $value) {
                array_push($teacher_list, Db::name('icr_teacher')->where('id',$value['tid'])->find());
            }
            return $teacher_list;
        }
    }

    /**
     * 获取课程教师交叉表id
     * @param $tid,$cid
     * @return
     */
    public function getTCID($tid,$cid)
    {
        $tcid = Db::name('icr_cteacher_intersect')
            ->where('cid',$cid)
            ->where('tid',$tid)
            ->select();
        return $tcid;
    }

    public function transformResumeToHtml(&$teacher)
    {
        $resume_html = "";
        //文字视频不同处理
        foreach (explode("\n", $teacher['resume']) as $item)
        {
            $resume_html .= $item . "<br/>";
            $resume_html .= "\n";
        }
        $teacher['resume'] = $resume_html;
    }
}