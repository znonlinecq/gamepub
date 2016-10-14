<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Auth; 

class IndexController extends Controller
{
    public function index()
    {
        if(Auth::check())
        {
            $data[] = array(
                    'title' => '首页部分',
                    'content' => '后台首页展示内容未知, 临时写一些说明文档。',); 
            $data[] = array(
                    'title' => '列表',
                    'content' => '现在每个列表都是10列，第一列ID, 最后一列操作, 每页默认显示25行，可以按照时间过滤, 搜索目前只能搜索某个固定列，如果需要更多列的选择, 过滤, 排序等功能之后在添加。',
                ); 
            $data[] = array(
                    'title' => '权限',
                    'content' => '功能权限根据角色设定，超级管理员默认拥有所有权限，添加用户的的时候可以选择角色，目前有三种角色，超级管理员、管理员、财务。可以添加更多角色。',); 
            $data[] = array(
                    'title' => '菜单',
                    'content' => '左侧菜单可以在模块管理里面添加、修改和排序，每个模块可以添加子功能，一条子功能对应一条权限.',);  
            $data[] = array(
                    'title' => '充值',
                    'content' => '给公会充值分两步，第一步下单，第二步支付.',); 
            
            $data[] = array(
                    'title' => '日志',
                    'content' => '目前记录的日志有公会、游戏、财务等部分的操作.',); 
            $data[] = array(
                    'title' => '未完成功能',
                    'content' => '订单管理部分、数据统计部分，图表展示，Excel、CSV文件导出.',); 
            

            return view('welcome', ['title'=>'Welcome', 'objects'=>$data]);
         //   $breadcrumbs = $this->breadcrumbs;
         //   View::share('dashboard',$breadcumbs);
         //   return view('dashboard', ["breadcrumbs"=>$breadcrumbs]);
        }
        else
        {
            return view('auth/login');
        } 
    }
}
