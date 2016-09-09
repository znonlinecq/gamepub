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
Route::resource('users', 'UserController');

//公会
Route::get('group_founders', 'GroupController@groupFounderList');
Route::get('groups', 'GroupController@groupList');


//应用
Route::get('applications', 'AppController@appList');
Route::get('application_blacklist', 'AppController@appBlacklist');

//角色
Route::resource('roles', 'RoleController');

//模块
Route::resource('modules', 'ModuleController');
Route::get('functions/{cid}', 'FunctionController@index');
Route::get('functions/create/{cid}', 'FunctionController@create');
Route::resource('functions', 'FunctionController');

//权限
Route::resource('permissions', 'PermissionController');

//会长审核&授权
Route::get('chairmans/list_not_audit', 'ChairmanController@list_not_audit');
Route::get('chairmans/audit_form/{id}', 'ChairmanController@audit_form');
Route::post('chairmans/audit_form_submit', 'ChairmanController@audit_form_submit');
Route::get('chairmans/list_audit', 'ChairmanController@list_audit');
Route::get('chairmans/game_authorization_form', 'ChairmanController@game_authorization_form');
Route::post('chairmans/game_authorization_form_submit', 'ChairmanController@game_authorization_form_submit');
Route::post('chairmans/list_not_audit_ajax', 'ChairmanController@list_not_audit_ajax');

//开发者
Route::get('developers', 'DeveloperController@index');
Route::post('developers/index_ajax', 'DeveloperController@index_ajax');
Route::get('developers/audit_form/{id}', 'DeveloperController@audit_form');
Route::post('developers/audit_form_submit', 'DeveloperController@audit_form_submit');

//游戏
Route::get('games', 'GameController@index');
Route::post('games/index_ajax', 'GameController@index_ajax');
Route::get('games/audit_form/{id}', 'GameController@audit_form');
Route::post('games/audit_form_submit', 'GameController@audit_form_submit');

//日志
Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');
Route::get('logs/{controller}/{method}', 'LogController@chairman_audit');

