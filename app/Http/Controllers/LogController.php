<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Models\Log;
use App\User;

class LogController extends Controller
{
    private $moduleRoute = 'logs';      //路由URL
    private $moduleView = 'log';        //视图路径
    private $moduleTable = 'logs';
    private $moduleName = '日志';

    public function chairman_audit($controllerType, $methodType)
    { 
        $controller  = ucfirst($controllerType).'Controller';
        $method     = $methodType;

        $logs = Log::where('module', $controller)
            ->where('function', $method)
            ->orderBy('created', 'desc')
            ->get();

        if(count($logs))
        {
            foreach($logs as $log)
            {
                $operator = User::find($log->uid);
                $log->created = date('Y-m-d H:i:s', $log->created);
                $log->operator = $operator->name;
                if($log->operation == 'yes')
                {
                    $log->operation = '通过';
                }else{
                    $log->operation = '驳回';
                }
                $log->person = $log->object;

            }
        }

        return view($this->moduleView.'/log', ['objects'=>$logs, 'title'=>'会长审核']);
    }
}
