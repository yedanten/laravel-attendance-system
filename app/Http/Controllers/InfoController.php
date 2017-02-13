<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Hash;
use App\Student;
use App\Teacher;

class InfoController extends Controller
{
    // 个人信息首页
    public function index(Request $request)
    {
        // 获取当前登录的身份信息
        $identity = $request->session()->get('identity');

        // 获取个人信息
        if ($identity == 'teacher') {
            $res = Teacher::where('workid', '=', Auth::guard('teacher')->id())->first();
        } else {
            $res = Student::where('stuid', '=', Auth::guard('student')->id())->first();
        }

        // 显示模板
        return view($identity.'.myinfo', ['info' => $res]);
    }

    // 更新信息
    public function update(Request $request)
    {
        // 获取当前登录的身份信息
        $identity = $request->session()->get('identity');

        // 获取个人信息
        if ($identity == 'teacher') {
            $model = Teacher::where('workid', '=', Auth::guard('teacher')->id())->first();
        } else {
            $model = Student::where('stuid', '=', Auth::guard('student')->id())->first();
        }
    }

    // 修改密码首页
    public function getPassword(Request $request)
    {
        // 获取当前登录的身份信息
        $identity = $request->session()->get('identity');

        return view($identity.'.password');
    }

    // 修改密码
    public function updatePassword(Request $request)
    {
        // 表单验证
        $this->validate($request, [
            'current_password' => 'required',
            'new_password' => 'required',
            'again_password' => 'required|same:new_password',
        ]);

        // 获取当前登录的身份信息
        $identity = $request->session()->get('identity');

        // 获取个人信息
        if ($identity == 'teacher') {
            $model = Teacher::where('workid', '=', Auth::guard('teacher')->id())->first();
        } else {
            $model = Student::where('stuid', '=', Auth::guard('student')->id())->first();
        }

        // 检查密码是否匹配
        if (!Hash::check($request->input('current_password'), $model->password)) {
            return 'error';
        }

        // 修改密码
        $model->password = bcrypt($request->input('new_password'));
        $model->save();
        return 'success';
    }

    // 绑定邮箱
    public function email(Request $request)
    {
        // 获取当前登录的身份信息
        $identity = $request->session()->get('identity');

        // 获取当前绑定的邮箱
        if ($identity == 'teacher') {
            $model = Teacher::where('workid', '=', Auth::guard('teacher')->id())->first();
        } else {
            $model = Student::where('stuid', '=', Auth::guard('student')->id())->first();
        }
        $email = $model->email;

        return view($identity.'.email', ['email' => $email]);
    }

    // 发送邮件
    public function sendMail(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email'
        ]);

        // 生成4位数的随机验证码
        $verify = rand(1000,9999);

        // 存入session
        $request->session()->put('verify', $verify);
        $request->session()->put('email', $request->input('email'));

        // 发送邮件
        $flag = Mail::send('Verify', ['name' => env('MAIL_FROM_NAME')], function ($message) use ($request) {
            $to = $request->input('email');;
            $message ->to($to)->subject('修改绑定邮箱验证码');
        });

        // 返回状态
        return 'success';
    }

    // 绑定/修改邮箱
    public function bindMail(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'verify' => 'required|numeric'
        ]);

        if ($request->input('verify') != $request->session()->get('verify') || $request->input('email') != $request->session()->get('email')) {
            return 'error';
        }

        // 获取当前登录的身份信息
        $identity = $request->session()->get('identity');

        // 获取模型
        if ($identity == 'teacher') {
            $model = Teacher::where('workid', '=', Auth::guard('teacher')->id())->first();
        } else {
            $model = Student::where('stuid', '=', Auth::guard('student')->id())->first();
        }

        // 保存信息
        $model->email = $request->input('email');
        $model->save();

        // 清除session
        $request->session()->forget('email');
        $request->session()->forget('verify');

        return 'success';
    }

    // 获取学生列表
    public function getStudent(Request $request)
    {	
    	if (!$request->input('subject')) {
    		return 'error';
    	} else {
    		$class = mb_substr($request->input('class'), 0, mb_strlen($request->input('class'))-1);
    		$list = Student::select('stuid', 'username')->where('class', '=', $class)->get();
    		return $list;
    	}
    }
}
