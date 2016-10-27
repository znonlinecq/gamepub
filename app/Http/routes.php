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
Route::get('chairmans',                                     'ChairmanController@index');
Route::get('chairmans/show/{id}',                           'ChairmanController@show');
Route::get('chairmans/audit_form/{id}',                     'ChairmanController@audit_form');
Route::post('chairmans/audit_form_submit',                  'ChairmanController@audit_form_submit');
Route::get('chairmans/game_authorization_form/{id}',        'ChairmanController@game_authorization_form');
Route::post('chairmans/game_authorization_form_submit',     'ChairmanController@game_authorization_form_submit');
Route::post('chairmans/index_ajax',                         'ChairmanController@index_ajax');
Route::get('chairmans/game_authorization',                  'ChairmanController@index');
Route::get('chairmans/blacklist',                           'ChairmanController@index');
Route::post('chairmans/blacklist_ajax',                     'ChairmanController@blacklist_ajax');
Route::get('chairmans/blacklist_join_form/{id}',            'ChairmanController@blacklist_join_form');
Route::post('chairmans/blacklist_join_form_submit',         'ChairmanController@blacklist_join_form_submit');
Route::get('chairmans/blacklist_out_form/{id}',             'ChairmanController@blacklist_out_form');
Route::post('chairmans/blacklist_out_form_submit',          'ChairmanController@blacklist_out_form_submit');


//开发者
Route::get('developers',                    'DeveloperController@index');
Route::post('developers/index_ajax',        'DeveloperController@index_ajax');
Route::get('developers/audit_form/{id}',    'DeveloperController@audit_form');
Route::post('developers/audit_form_submit', 'DeveloperController@audit_form_submit');

//游戏
Route::get('games',                                 'GameController@index');
Route::post('games/index_ajax',                     'GameController@index_ajax');
Route::get('games/audit_form/{id}',                 'GameController@audit_form');
Route::post('games/audit_form_submit',              'GameController@audit_form_submit');
Route::get('games/types',                           'GameController@types');
Route::post('games/types_ajax',                     'GameController@types_ajax');
Route::get('games/types_add',                       'GameController@types_add');
Route::post('games/types_add_submit',               'GameController@types_add_submit');
Route::get('games/types_edit/{id}',                 'GameController@types_edit');
Route::post('games/types_edit_submit',              'GameController@types_edit_submit');
Route::get('games/types_delete/{id}',               'GameController@types_delete');
Route::get('games/types/classes/{id?}',             'GameController@types_classes');
Route::post('games/types/classes_ajax',             'GameController@types_classes_ajax');
Route::get('games/types/classes_add/{id?}',         'GameController@types_classes_add');
Route::post('games/types/classes_add_submit',       'GameController@types_classes_add_submit');
Route::get('games/types/classes_edit/{id}',         'GameController@types_classes_edit');
Route::post('games/types/classes_edit_submit',      'GameController@types_classes_edit_submit');
Route::get('games/types/classes_delete/{id}',       'GameController@types_classes_delete');
Route::get('games/rebates',                         'GameController@rebate');
Route::post('games/rebates_ajax',                   'GameController@rebate_ajax');
Route::get('games/rebate_setup_form/{id}',          'GameController@rebate_setup_form');
Route::post('games/rebate_setup_form_submit',       'GameController@rebate_setup_form_submit');
Route::get('games/online',                          'GameController@online');
Route::post('games/online_ajax',                    'GameController@online_ajax');
Route::get('games/online_handle_submit/{id}/{status}',  'GameController@online_handle_submit');
Route::get('games/blacklist',                           'GameController@blacklist');
Route::post('games/blacklist_ajax',                     'GameController@blacklist_ajax');
Route::get('games/blacklist_form/{id}/{type}',                 'GameController@blacklist_form');
Route::post('games/blacklist_form_submit',                 'GameController@blacklist_form_submit');



//游戏包
Route::get('apks',                                  'ApkController@index');
Route::post('apks/index_ajax',                      'ApkController@index_ajax');
Route::get('apks/audit_form/{id}',                  'ApkController@audit_form');
Route::post('apks/audit_form_submit',               'ApkController@audit_form_submit');
Route::get('apks/sdks',                             'SdkController@index');
Route::post('apks/sdks/index_ajax',                 'SdkController@index_ajax');
Route::get('apks/sdks/audit_form/{id}',             'SdkController@audit_form');
Route::post('apks/sdks/audit_form_submit',          'SdkController@audit_form_submit');
Route::get('apks/download/{id}',                    'ApkController@download');


//日志
Route::get('logs',                                  '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');
Route::get('logs/{controller}/{method}',            'LogController@index');
Route::post('logs/index_ajax',                      'LogController@index_ajax');

//财务
Route::get('finances',                                  'FinanceController@index');
Route::post('finances/index_ajax',                      'FinanceController@index_ajax');
Route::get('finances/recharge_form',                    'FinanceController@recharge_form');
Route::post('finances/recharge_form_submit',            'FinanceController@recharge_form_submit');
Route::get('finances/discount_form',                    'FinanceController@discount_form');
Route::post('finances/discount_form_submit',            'FinanceController@discount_form_submit');
Route::get('finances/order_pay_form/{id}',              'FinanceController@order_pay_form');
Route::post('finances/order_pay_form_submit',           'FinanceController@order_pay_form_submit');

//订单
Route::get('orders',                                            'OrderController@index');
Route::post('orders/index_ajax',                                'OrderController@index_ajax');
Route::get('orders/recharge/{id}',                              'OrderController@recharge_show');
Route::get('orders/payments',                                   'OrderController@payment_orders');
Route::post('orders/payments_ajax',                             'OrderController@payment_orders_ajax');
Route::get('orders/payments/{id}',                              'OrderController@payment_show');
Route::get('orders/gives',                                      'OrderController@give_orders');
Route::post('orders/gives_ajax',                                'OrderController@give_orders_ajax');
Route::get('orders/gives/{id}',                                 'OrderController@give_show');

//验证码
Route::get('kit/captcha/{tmp}', 'KitController@captcha');

//创建表
Route::get('create_table/statistic_guild_user',                 'Guild\StatisticUserController@create_table');

//公会统计
Route::get('chairmans/statistic/users',                     'Guild\StatisticUserController@index');
Route::post('chairmans/statistic/users_ajax',               'Guild\StatisticUserController@index_ajax');
Route::get('chairmans/statistic/users/{id}',                'Guild\StatisticUserController@show');
Route::get('chairmans/statistic/users_guilds',              'Guild\StatisticUserGuildController@index');
Route::post('chairmans/statistic/users_guilds_ajax',        'Guild\StatisticUserGuildController@index_ajax');
Route::get('chairmans/statistic/users_guilds/{id}',         'Guild\StatisticUserGuildController@show');
Route::get('chairmans/statistic/games_recharges',           'Guild\StatisticGameRechargeController@index');
Route::post('chairmans/statistic/games_recharges_ajax',     'Guild\StatisticGameRechargeController@index_ajax');
Route::get('chairmans/statistic/games_recharges/{id}',      'Guild\StatisticGameRechargeController@show');
Route::get('chairmans/statistic/games_consumes',            'Guild\StatisticGameConsumeController@index');
Route::post('chairmans/statistic/games_consumes_ajax',      'Guild\StatisticGameConsumeController@index_ajax');
Route::get('chairmans/statistic/games_consumes/{id}',       'Guild\StatisticGameConsumeController@show');
Route::get('chairmans/statistic/badous_consumes',            'Guild\StatisticBadouConsumeController@index');
Route::post('chairmans/statistic/badous_consumes_ajax',      'Guild\StatisticBadouConsumeController@index_ajax');
Route::get('chairmans/statistic/badous_consumes/{id}',       'Guild\StatisticBadouConsumeController@show');

//数据统计
Route::get('statistics/{type}',                  'StatisticController@index_type');
Route::post('statistics/ajax/{type}',            'StatisticController@index_ajax_type');
Route::get('statistics/{type}/{id}',             'StatisticController@show_type');


