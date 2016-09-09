@extends('dashboard')
@section('content')
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
            @if(session('message'))
            <p class="bg-success">{{session('message')}}</p>
            @endif    
            <!-- /.box-header -->
            <div class="box-body">
              <table id="tableList" class="table table-bordered table-striped" data-page-length='25'  >
                <thead>
                <tr>
                  <th>ID</th>
                  <th>登录账号</th>
                  <th>工会ID</th>
                  <th>推广游戏</th>
                  <th>姓名</th>
                  <th>身份证</th>
                  <th>QQ</th>
                  <th>注册时间</th>
                  <th>状态</th>
                  <th>操作</th>
                </tr>
                </thead>
                <tbody>
                 </tbody>
              </table>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    <!-- /.content -->
  </div>
<!-- DataTables -->
<script src="{{ asset('resources/plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('resources/plugins/datatables/dataTables.bootstrap.min.js') }}"></script>
<!-- SlimScroll -->
<script src="{{ asset('resources/plugins/slimScroll/jquery.slimscroll.min.js') }}"></script>
<!-- FastClick -->
<script src="{{ asset('resources/plugins/fastclick/fastclick.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('resources/dist/js/app.min.js') }}"></script>
<!-- AdminLTE for demo purposes -->
<script src="{{ asset('resources/dist/js/demo.js') }}"></script>

<script src="{{ asset('resources/plugins/daterangepicker/moment.js') }}"></script>
<script src="{{ asset('resources/plugins/daterangepicker/daterangepicker.js') }}"></script>
<!-- page script -->
<script>
$(function () {
    var host = window.location.host;
    var languageUrl = '/chinese.json';
    var localUrl = 'http://localhost/gamepub/public';
    var ajaxUrl = '/chairmans/list_not_audit_ajax';

    if(host == 'localhost')
    {
       languageUrl = localUrl + languageUrl; 
       ajaxUrl = localUrl + ajaxUrl;
    }

  var table =  $("#tableList").DataTable({
        order: [[0,'asc']],
        columns:[
            {"orderable":false},
            {"orderable":false},
            {"orderable":false},
            {"orderable":false},
            {"orderable":false},
            {"orderable":false},
            {"orderable":false},
            {"orderable":true},
            {"orderable":false},
            {"orderable":false},
        ],
        language: {
            url: languageUrl,
        },
        serverSide: true,    
        ajax: {
            url: ajaxUrl,
            type: 'POST',
            data: {"name":"test"},
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
        },
        "dom":"<'row'<'span9'l<'#mytoolbox'>><'span3'f>r>"+"t"+"<'row'<'span6'i><'span6'p>>",
        initComplete:initComplete,
    });
/**
    * 表格加载渲染完毕后执行的方法
    * @param data
    */
   function initComplete(data){
 
       var dataPlugin =
               '<div id="reportrange" class="pull-left dateRange" style="width:400px;margin-left: 10px"> '+
               '日期：<i class="glyphicon glyphicon-calendar fa fa-calendar"></i> '+
               '<span id="searchDateRange"></span>  '+
               '<b class="caret"></b></div> ';
       $('#mytoolbox').append(dataPlugin);
       //时间插件
       $('#reportrange span').html(moment().subtract('hours', 1).format('YYYY-MM-DD HH:mm:ss') + ' - ' + moment().format('YYYY-MM-DD HH:mm:ss'));
 
       $('#reportrange').daterangepicker(
               {
                   // startDate: moment().startOf('day'),
                   //endDate: moment(),
                   //minDate: '01/01/2012',    //最小时间
                   maxDate : moment(), //最大时间
                   dateLimit : {
                       days : 30
                   }, //起止时间的最大间隔
                   showDropdowns : true,
                   showWeekNumbers : false, //是否显示第几周
                   timePicker : true, //是否显示小时和分钟
                   timePickerIncrement : 60, //时间的增量，单位为分钟
                   timePicker12Hour : false, //是否使用12小时制来显示时间
                   ranges : {
                       //'最近1小时': [moment().subtract('hours',1), moment()],
                       '今日': [moment().startOf('day'), moment()],
                       '昨日': [moment().subtract('days', 1).startOf('day'), moment().subtract('days', 1).endOf('day')],
                       '最近7日': [moment().subtract('days', 6), moment()],
                       '最近30日': [moment().subtract('days', 29), moment()]
                   },
                   opens : 'right', //日期选择框的弹出位置
                   buttonClasses : [ 'btn btn-default' ],
                   applyClass : 'btn-small btn-primary blue',
                   cancelClass : 'btn-small',
                   format : 'YYYY-MM-DD HH:mm:ss', //控件中from和to 显示的日期格式
                   separator : ' to ',
                   locale : {
                       applyLabel : '确定',
                       cancelLabel : '取消',
                       fromLabel : '起始时间',
                       toLabel : '结束时间',
                       customRangeLabel : '自定义',
                       daysOfWeek : [ '日', '一', '二', '三', '四', '五', '六' ],
                       monthNames : [ '一月', '二月', '三月', '四月', '五月', '六月',
                           '七月', '八月', '九月', '十月', '十一月', '十二月' ],
                       firstDay : 1
                   }
               }, function(start, end, label) {//格式化日期显示框
 
                   $('#reportrange span').html(start.format('YYYY-MM-DD HH:mm:ss') + ' - ' + end.format('YYYY-MM-DD HH:mm:ss'));
               });
 
       //设置日期菜单被选项  --开始--
       var dateOption ;
       if("${riqi}"=='day') {
           dateOption = "今日";
       }else if("${riqi}"=='yday') {
           dateOption = "昨日";
       }else if("${riqi}"=='week'){
           dateOption ="最近7日";
       }else if("${riqi}"=='month'){
           dateOption ="最近30日";
       }else if("${riqi}"=='year'){
           dateOption ="最近一年";
       }else{
           dateOption = "自定义";
       }
       $(".daterangepicker").find("li").each(function (){
           if($(this).hasClass("active")){
               $(this).removeClass("active");
           }
           if(dateOption==$(this).html()){
               $(this).addClass("active");
           }
       });
       //设置日期菜单被选项  --结束--
 
 
       //选择时间后触发重新加载的方法
       $("#reportrange").on('apply.daterangepicker',function(){
           //当选择时间后，出发dt的重新加载数据的方法
           table.ajax.reload();
           //获取dt请求参数
           var args = table.ajax.params();
           console.log("额外传到后台的参数值extra_search为："+args.extra_search);
       });
 
       function getParam(url) {
           var data = decodeURI(url).split("?")[1];
           var param = {};
           var strs = data.split("&");
 
           for(var i = 0; i<strs.length; i++){
               param[strs[i].split("=")[0]] = strs[i].split("=")[1];
           }
           return param;
       }
   }
});
</script>

<div id="dialog-confirm" style="display: none;" title="提示">
          <p><span class="ui-icon ui-icon-alert" style="float:left; margin:12px 12px 20px 0;"></span><br>您确定要执行此操作吗?</p>
</div>
@endsection
