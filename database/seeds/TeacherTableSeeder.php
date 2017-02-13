<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Teacher;

class TeacherTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $teacher = new Teacher;
        $teacher->workid = 10000;
        $teacher->username = '程伍端';
        $teacher->password = bcrypt(10000);
        $teacher->save();
    }
}