<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class IndexController extends Controller
{
    public function index(Request $request)
    {
        // 获取当前登录的身份信息
        $identity = $request->session()->get('identity');
        return view($identity.'.index');
    }

    // 登出
    public function logout(Request $request)
    {
        $request->session()->forget('identity');
    	auth()->guard('student')->logout();
    	auth()->guard('teacher')->logout();
    	return redirect('/');
    }
}
