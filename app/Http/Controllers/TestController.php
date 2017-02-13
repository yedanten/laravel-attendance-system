<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class TestController extends Controller
{
    public function index(Request $request)
    {
    	$first = DB::table('class')->where('class', 'like', '%'.'4104'.'%');
    	$second = DB::table('class')->where('department', 'like', '%'.'4104'.'%');
        $res = DB::table('class')->where('id', 'like', '%'.'4104'.'%')->union($first)->union($second)->get();
    	dump($res);
    }
}
