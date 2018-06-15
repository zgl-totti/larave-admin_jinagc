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


Auth::routes();
Route::get('auth/login_wechat','Auth\LoginController@loginWechat');
Route::get('auth/login_qq','Auth\LoginController@loginQq');
/*Route::group(['middleware' => ['web', 'wechat.oauth']], function () {
    Route::get('/user', function () {
        $user = session('wechat.oauth_user'); // 拿到授权用户资料

        dd($user);
    });
});*/


Route::group(['namespace'=>'Index'],function (){

    Route::get('/home', 'HomeController@index')->name('home');
    Route::get('roma/index', 'RomaController@index');
});
