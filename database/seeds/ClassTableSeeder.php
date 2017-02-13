<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClassTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::connection('czjt')->select(DB::raw("SET sql_mode=(SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''));"));
    	$data = DB::connection('czjt')->table('user')->select('id', 'department', 'major')->groupBy('major')->get();
	    foreach ($data as $key => $value) {
            $res = DB::table('class')->where('id', '=', mb_substr($value->id, 2, 4))->get();
            $res = $res->toArray();
            if ($res) {
                continue;
            }
	    	DB::table('class')->insert([
                'id' => mb_substr($value->id, 2, 4),
	    		'department' => $value->department,
	    		'class' => $value->major
            ]);
	    }
    }
}
