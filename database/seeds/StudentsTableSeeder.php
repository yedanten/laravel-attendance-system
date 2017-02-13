<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StudentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $res = DB::connection('czjt')->table('user')->get();
        foreach ($res as $key => $value) {
        	DB::table('students')->insert([
        		'stuid' => $value->stu_id,
        		'username' => $value->username,
        		'password' => bcrypt($value->stu_id),
        		'sex' => $value->sex,
        		'department' => $value->department,
        		'major' => $value->major,
        		'class' => $value->class
        	]);
        }
    }
}
