<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesResources;
use App\Models\Menu;
use View;
use Auth;

class Controller extends BaseController
{
    use AuthorizesRequests, AuthorizesResources, DispatchesJobs, ValidatesRequests;

    public $breadcrumbs;

    public function __construct()
    {
        if(Auth::check())
        {
            View::composer('*', function ($view) {
                $breadcrumbs = array(
                    '/' => 'Dashboard'
                );
                $view->with('breadcrumbs', $breadcrumbs);
            });
            View::composer('layouts/sidebar', function($view){
                $menus = Menu::menuLoad();
                $view->with('menus', $menus);
            });
        
        }
    }

    public function set_breadcrumbs($data)
    {
        foreach($data AS $key => $value)
        {
            $this->breadcrumbs[$key] = $value;
        }
    }
    
    public function get_breadcrumbs()
    {
        return $this->breadcrumbs;
    }

    public function page_empty()
    {
        abort(503);
    }
    
    /**
     * 提交GET请求，curl方法
     * @param string  $url	   请求url地址
     * @param mixed   $data	  GET数据,数组或类似id=1&k1=v1
     * @param array   $header	头信息
     * @param int	 $timeout   超时时间
     * @param int	 $port	  端口号
     * @return array			 请求结果,
     *							如果出错,返回结果为array('error'=>'','result'=>''),
     *							未出错，返回结果为array('result'=>''),
     */
    function curl_get($url, $data = array(), $header = array(), $timeout = 5, $port = 80)
    {
        $ch = curl_init();
        if (!empty($data)) {
            $data = is_array($data)?http_build_query($data): $data;
            $url .= (strpos($url,'?')?  '&': "?") . $data;
        }
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_POST, 0);
        //curl_setopt($ch, CURLOPT_PORT, $port);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION ,1); //是否抓取跳转后的页面
        $result = curl_exec($ch);
        if (0 != curl_errno($ch)) {
            $result = "Error:\n" . curl_error($ch);
        }
        curl_close($ch);
        return $result;
    }
    /**
     * 提交POST请求，curl方法
     * @param string  $url	   请求url地址
     * @param mixed   $data	  POST数据,数组或类似id=1&k1=v1
     * @param array   $header	头信息
     * @param int	 $timeout   超时时间
     * @param int	 $port	  端口号
     * @return string			请求结果,
     *							如果出错,返回结果为array('error'=>'','result'=>''),
     *							未出错，返回结果为array('result'=>''),
     */
    function curl_post($url, $data = array(), $header = array(), $timeout = 5, $port = 80)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        //curl_setopt($ch, CURLOPT_PORT, $port);
        !empty ($header) && curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $result = curl_exec($ch);
        if (0 != curl_errno($ch)) {
            $result  = "Error:\n" . curl_error($ch);
        }
        curl_close($ch);
        return $result;
    }
}
