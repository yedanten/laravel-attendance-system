<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Excel;
use App\Student;
use App\Teacher;
use App\Named_log;

class AttendController extends Controller
{
    public function index(Request $request)
    {
        // 获取当前登录的身份信息
        $identity = $request->session()->get('identity');
        return view($identity.'.attend');
    }

    public function named(Request $request)
    {
        // 获取当前登录的身份信息
        $identity = $request->session()->get('identity');
        return view($identity.'.named');
    }

    public function storeNamed(Request $request)
    {
		$current_time = intval(date('Hi'));
        $No = 0;
        // 夏季作息时间
        if (5 < intval(date('n'))  &&  intval(date('n')) < 11) {
            // 排下
            if ($request->input('area') == '排下校区') {
                if (799 < $current_time  &&  $current_time < 955) {
                    $No = 1;
                } elseif (999 < $current_time  &&  $current_time < 1140) {
                    $No = 2;
                } elseif (1429 < $current_time  &&  $current_time < 1615) {
                    $No = 3;
                } elseif (1629 < $current_time  &&  $current_time < 1815) {
                    $No = 4;
                }
            // 首山
            } else {
                if (799 < $current_time  &&  $current_time < 945) {
                    $No = 1;
                } elseif (999 < $current_time  &&  $current_time < 1145) {
                    $No = 2;
                } elseif (1429 < $current_time  &&  $current_time < 1615) {
                    $No = 3;
                } elseif (1629 < $current_time  &&  $current_time < 1815) {
                    $No = 4;
                }
            }

        // 冬季作息时间
        } else {
            // 排下
            if ($request->input('area') == '排下校区') {
               if (799 < $current_time  &&  $current_time < 955) {
                    $No = 1;
                } elseif (999 < $current_time  &&  $current_time < 1140) {
                    $No = 2;
                } elseif (1399 < $current_time  &&  $current_time < 1545) {
                    $No = 3;
                } elseif (1599 < $current_time  &&  $current_time < 1745) {
                    $No = 4;
                }
            // 首山
            } else {
                if (799 < $current_time  &&  $current_time < 955) {
                    $No = 1;
                } elseif (999 < $current_time  &&  $current_time < 1140) {
                    $No = 2;
                } elseif (1399 < $current_time  &&  $current_time < 1545) {
                    $No = 3;
                } elseif (1599 < $current_time  &&  $current_time < 1745) {
                    $No = 4;
                }
            }
        }
    	if (!$No) {
            $request->session()->flash('notclasstime', '现在不是上课时间');
            return back();
        }
        $class = mb_substr($request->input('class'), 0, mb_strlen($request->input('class'))-1);
        $allStudent = Student::where('class', '=', $class)->get();
        foreach ($allStudent as $key => $value) {
            if (in_array($value['stuid'], $request->input('stuid'), false)) {
            	$named_log = Named_log::create([
            		'workid' => Auth::guard('teacher')->id(),
                    'class' => $request->input('class'),
                    'subject' => $request->input('subject'),
                    'No' => $No,
                    'area' => $request->input('area'),
                    'stuid' => $value['stuid'],
                    'attend' => 0,
                    'time' => time()
            	]);
            } else {
            	$named_log = Named_log::create([
            		'workid' => Auth::guard('teacher')->id(),
                    'class' => $request->input('class'),
                    'subject' => $request->input('subject'),
                    'No' => $No,
                    'area' => $request->input('area'),
                    'stuid' => $value['stuid'],
                    'attend' => 1,
                    'time' => time()
            	]);
            }
        }
        $request->session()->flash('message', '点名成功');
        return redirect()->action('AttendController@named');
    }

    // 历史记录 首页
    public function history(Request $request)
    {
        // 获取当前登录的身份信息
        $identity = $request->session()->get('identity');
        return view($identity.'.history');
    }

    // 历史点名记录
    public function show(Request $request, $daterange, $class = '%', $subject = '%')
    {
        // 参数预处理

        // 分割时间区间
        $start_time = strtotime(explode(' - ', $daterange)[0]);
        $end_time = strtotime(explode(' - ', $daterange)[1]);

        // 同一天，结束时间加24小时
        if ($start_time == $end_time) {
            $end_time += 86400;
        }

        // 获取当前登录的身份信息
        $identity = $request->session()->get('identity');

        // 组装查询条件
        if ($identity == 'teacher') {
            $condition = ['workid', '=', Auth::guard('teacher')->id()];
        } else {
            $condition = ['stuid', '=', Auth::guard('student')->id()];
        }

        // 查询结果集
        $res = Named_log::with('student', 'teacher')->where([
            $condition,
            ['class', 'like', $class],
            ['subject', 'like', $subject]
        ])
        ->whereBetween('time', [$start_time, $end_time])
        ->get();

        //dump($res);
        // 闪存数据
        $request->session()->flash('named_log', $res);

        // 返回数据
       return $res;
    }

    // 导出excel
    public function export(Request $request)
    {
        $cellData = [
            ['班级', '学号', '姓名', '课程名称', '第几节大课', '出勤情况', '点名时间', '教师']
        ];

        foreach ($request->session()->get('named_log') as $key => $value) {
            if ($value->attend == 0) {
                $value->attend = '缺勤';
            } else {
                $value->attend = '出勤';
            }
            $cellData[] = [
                $value->class,
                $value->stuid,
                $value->student->username,
                $value->subject,
                $value->No,
                $value->attend,
                $value->created_at,
                $value->teacher->username
            ];
        }
        Excel::create('出勤历史', function ($excel) use ($cellData) {
            $excel->sheet('出勤历史', function ($sheet) use ($cellData) {
                $sheet->rows($cellData);
            });
        })->export('xls');
    }
}
