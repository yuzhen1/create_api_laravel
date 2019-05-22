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

Route::get('/', function () {
    return view('welcome');
});
Route::get('/phpinfo', function () {
    return view('phpinfo');
});
Route::get('/reg/reg','RegisterController@reg');//注册
Route::post('/reg/reg_do','RegisterController@reg_do');//注册执行
Route::get('/reg/create_token','RegisterController@create_token');//access_token
Route::get('/reg/Ip','RegisterController@Ip');//Ip
Route::get('/reg/UA','RegisterController@UA');//UA
Route::get('/reg/test','RegisterController@test');//测试
Route::get('/reg/firm_list','RegisterController@firm_list');//企业信息