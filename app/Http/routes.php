<?php
use App\User;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', 'IndexController@index');

#Route::get('dashboard', function () {
#     return view('dashboard');
#});
#Route::get('test', 'TestController@index');

// 认证路由...
Route::get('auth/login', 'Auth\AuthController@getLogin');
Route::post('auth/login', 'Auth\AuthController@login');
Route::get('auth/logout', 'Auth\AuthController@logout');

// 注册路由...
Route::get('auth/register', 'Auth\AuthController@getRegister');
Route::post('auth/register', 'Auth\AuthController@postRegister');

// 密码重置链接的路由...
Route::get('password/email', 'Auth\PasswordController@getEmail');
Route::post('password/email', 'Auth\PasswordController@postEmail');

// 密码重置的路由...
Route::get('password/reset/{token}', 'Auth\PasswordController@getReset');
Route::post('password/reset', 'Auth\PasswordController@postReset');


#Route::auth();

//用户
Route::get('users', 'UserController@index');
Route::get('user/{id}/edit', 'UserController@edit');
Route::post('user/{id}/edit', 'UserController@update');

//公会
Route::get('group_founders', 'GroupController@groupFounderList');
Route::get('groups', 'GroupController@groupList');


//应用
Route::get('applications', 'AppController@appList');
Route::get('application_blacklist', 'AppController@appBlacklist');


//角色
//Route::get('roles', 'RoleController@roleList');
Route::resource('roles', 'RoleController');

//权限
Route::get('permissions', 'PermissionController@permissionList');


