<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use App\Student;
use App\Teacher;

class LessonController extends Controller
{
    // 课程首页
    public function index(Request $request)
    {
        // 获取所有课程列表
        $class_list = Student::groupBy('major')->orderBy('department', 'asc')->paginate(15);
        
        // 获取当前登录的身份信息
        $identity = $request->session()->get('identity');
        return view($identity.'.lesson', ['class' => $class_list]);
    }

    // 课程搜索
    public function search(Request $request)
    {
        // 获取输入的关键词
    	$input = $request->input('input');

        // 查找
        $res = Student::select('major', 'department')->where('class', 'like', '%'.$input.'%')
                                                     ->orWhere('department', 'like', '%'.$input.'%')
                                                     ->groupBy('major')
                                                     ->get();
    	return $res;
    }

    // 获取教师的教授课程列表
    public function getSubject()
    {
        $subject_list = unserialize(Teacher::where('workid', '=', Auth::guard('teacher')->id())->value('subject'));
        if ($subject_list) {
            return $subject_list;
        } else {
            return 'null';
        }
    }

    // 添加教师教授课程
    public function storeSubject(Request $request)
    {
        // 查询数据库是否已经有课程
        $old = Teacher::where('workid', '=', Auth::guard('teacher')->id())->value('subject');

        // 取出模型
        $teacher = Teacher::where('workid', '=', Auth::guard('teacher')->id())->first();

        // 如果有
        if ($old) {
            // 追加数据
            $old_r = unserialize($old);
            array_push($old_r, $request->input('input'));
            $old = serialize($old_r);
            $teacher->subject = $old;
            $teacher->save();
        // 否则
        } else {
            // 直接写入
            $data = serialize([$request->input('input')]);
            $teacher->subject = $data;
            $teacher->save();
        }
        return 'success';
    }

    public function delSubject(Request $request, $id)
    {
         // 取出模型
        $teacher = Teacher::where('workid', '=', Auth::guard('teacher')->id())->first();

        // 反序列化
        $old = unserialize($teacher->subject);

        // 删除对应的数据
        unset($old[$id]);

        // 下标重新排序并序列号
        $old = serialize(array_merge($old));

        // 修改
        $teacher->subject = $old;
        $teacher->save();

        // 返回
        return 'success';
    }

    // 添加授课信息
    public function storeTeach(Request $request)
    {
        // 取出模型
        $teacher = Teacher::where('workid', '=', Auth::guard('teacher')->id())->first();
        
        // 构造数据
        $teach = [
            'class' => substr($request->input('year'), 2).$request->input('classname').$request->input('classnum'),
            'subject' => $request->input('subject')
        ];

        // 查询数据库是否已经有授课列表
        $old = $teacher->teach;

        // 如果有
        if ($old) {
            // 追加数据
            $old_r = unserialize($old);
            array_push($old_r, $teach);
            $old = serialize($old_r);
            $teacher->teach = $old;
        // 否则
        } else {
            $teach = serialize([$teach]);
            $teacher->teach = $teach;
        }

        // 写入
        $teacher->save();
        return redirect()->action('LessonController@index');
    }

    // 删除授课信息
    public function delTeach(Request $request, $id)
    {
        // 取出模型
        $teacher = Teacher::where('workid', '=', Auth::guard('teacher')->id())->first();

        // 反序列化
        $old = unserialize($teacher->teach);

        // 删除指定数据
        unset($old[$id]);

        // 下标重新排序，并序列化
        $old = serialize(array_merge($old));

        // 保存
        $teacher->teach = $old;
        $teacher->save();

        return 'success';
    }

    // 查看授课信息列表
    public function getTeach()
    {
        // 取出模型
        $teacher = Teacher::where('workid', '=', Auth::guard('teacher')->id())->first();

        // 反序列化
        $teach_list = unserialize($teacher->teach);

        if ($teach_list) {
            return $teach_list;
        } else {
            return null;
        }
    }
}
