<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::group(['middleware' => 'auth.admin'], function () {
	Route::get('/','IndexController@index');
	Route::get('/lesson', 'LessonController@index');
	Route::get('/lesson/subject', 'LessonController@getSubject');
	Route::post('/lesson/subject', 'LessonController@storeSubject');
	Route::delete('/lesson/subject/{id}', 'LessonController@delSubject');
	Route::get('/teach', 'LessonController@getTeach');;
	Route::post('/teach', 'LessonController@storeTeach');
	Route::delete('/teach/{id}', 'LessonController@delTeach');
	Route::get('/attend/named', 'AttendController@named');
	Route::post('/attend/named', 'AttendController@storeNamed');
	Route::get('/attend/history', 'AttendController@history');
	Route::get('/attend/history/export', 'AttendController@export');
	Route::get('/attend/history/{daterange}/{class?}/{subject?}', 'AttendController@show');
	Route::get('/myinfo', 'InfoController@index');
	Route::get('/password', 'InfoController@getPassword');
	Route::post('/password', 'InfoController@updatePassword');
	Route::get('/email', 'InfoController@email');
	Route::post('/email/sendmail', 'InfoController@sendMail');
	Route::post('/email', 'InfoController@bindMail');
});
Route::any('/logout', 'IndexController@logout');