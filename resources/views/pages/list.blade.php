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
                    @foreach($tableTitles as $title)
                        <th>{{$title}}</th>
                    @endforeach
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

<!-- CSS -->
<link rel="stylesheet" href="{{ asset('resources/plugins/daterangepicker/daterangepicker.css') }}">
<style>
    #mytoolbox {height:30px; line-height:30px;}

    .showslider {
            width: 80%;
            height: 100%;
            background-color: #fff;
            border: 1px solid #ccc;
            position: absolute;
            top: 9px;
        }
 
        .addselect {
            border-radius: 2px;
            display: inline-block;
            background-color: #ccc;
            height: 15px;
            width: 16px;
            text-align: center;
            color: #fff;
            font-size: 9px;
            font-family: Arial;
            position: relative;
            margin-left: 4px;
            cursor: pointer;
            overflow: hidden;
            vertical-align: top;
            top: 2px;
        }
 
        .addselect select {
            width: 44px;
            opacity: 0;
            position: absolute;
            left: 0;
            top: 0;
            cursor: pointer;
        }
 
  
</style>

<!-- JS -->
<script src="{{ asset('resources/plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('resources/plugins/datatables/dataTables.bootstrap.min.js') }}"></script>
<script src="{{ asset('resources/plugins/daterangepicker/moment.js') }}"></script>
<script src="{{ asset('resources/plugins/daterangepicker/daterangepicker.js') }}"></script>
<!-- <script src="{{ asset('resources/js/daterangeconfig.js') }}"></script> -->


<script>
$(function () {
    var host        = window.location.host;
    var languageUrl = '{{$languageUrl}}';
    var localUrl    = '{{$localUrl}}';
    var ajaxUrl     = '{{$moduleAjax}}';
    var advanceSearchBox    = '{{$advanceSearchBox}}';
    var advanceSearchFields    = '{{$advanceSearchFields}}';
    if(advanceSearchFields)
    {
        advanceSearchFields = advanceSearchFields.replace(/&quot;/g,"\'");
        advanceSearchFields = eval('(' + advanceSearchFields + ')'); 
    }
    var isAdvanceSearch = '{{$isAdvanceSearch}}';
    if(host == 'localhost')
    {
       languageUrl  = localUrl + languageUrl; 
       ajaxUrl      = localUrl + ajaxUrl;
    }
    var orderStr        = '{{$tableOrder}}';
    var orderArray      = orderStr.split(","); 
    var columnsStr      = '{{$tableColumns}}';
    var columnsArray    = columnsStr.split(",");
    for (var i = 0; i < columnsArray.length; i++) {
        var cObject = {};
        if(columnsArray[i] == 'false')
        {
            cObject.orderable = false;
        }
        else
        {
            cObject.orderable = true;
        }
        columnsArray[i] = cObject;
    }
    var dateFilter = '{{$dateFilter}}';
    if(dateFilter)
    {
        if(isAdvanceSearch)
        {
            var dom = "<'row'<'col-sm-1'l><'col-sm-7'<'#mytoolbox'>><'col-sm-4' <'#searchbox'>>>"+"<'row'<'col-sm-12'<'#advanceSearchBox'>>>"+"t"+"<'row'<'col-sm-6'i><'col-sm-6'p>>";
        }
        else
        {
            var dom = "<'row'<'col-sm-1'l><'col-sm-7'<'#mytoolbox'>><'col-sm-4' <'#searchbox'>>>"+"t"+"<'row'<'col-sm-6'i><'col-sm-6'p>>"; 
        }
    }
    else
    { 
        if(isAdvanceSearch)
        {
            var dom = "<'row'<'col-sm-1'l><'col-sm-7'><'col-sm-4' <'#searchbox'>>>"+"<'row'<'col-sm-12'<'#advanceSearchBox'>>>"+"t"+"<'row'<'col-sm-6'i><'col-sm-6'p>>";
        }
        else
        {
            var dom = "<'row'<'col-sm-1'l><'col-sm-7'><'col-sm-4' <'#searchbox'>>>"+"t"+"<'row'<'col-sm-6'i><'col-sm-6'p>>"; 
        }
    }
    var table =  $("#tableList").DataTable({
        order: [orderArray],
        columns: columnsArray,
        language: {
            url: languageUrl,
            searchPlaceholder: '{{$searchPlaceholder}}',
        },
        bFilter: false, //列筛序功能
        searching: true,//本地搜索
        ordering: true, //排序功能
        serverSide: true,    
        ajax: {
            url: ajaxUrl,
            type: 'POST',
            data: function (d){
                d.dateRange = $('#reportrange span').html();
                d.searchKeyword = $('#searchKeyword').val();
                var searchFields = [];
                var i =0;
                for(var key in advanceSearchFields){
                    var a = {};
                    a[key] = $('#'+key).val();
                    searchFields[i] = a;
                    i++;
                }       
                d.searchFields = searchFields;
                console.log(d);
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
        }, 
        dom: dom,
        
        initComplete:initComplete,
    });
           //添加索引列
           table.on('order.dt search.dt',
                   function () {
                       table.column(0, {
                           search: 'applied',
                           order: 'applied'
                       }).nodes().each(function (cell, i) {
                           cell.innerHTML = i + 1;
                       });
                   }).draw();
});


/**
 * 表格加载渲染完毕后执行的方法
 * @param data
 */
function initComplete(data){

    var dataPlugin =
        '<div id="reportrange" class="pull-left dateRange" style="width:400px;margin-left: 10px"> '+
        '<button class="btn btn-default" >选择日期</button>&nbsp;<i class="glyphicon glyphicon-calendar fa fa-calendar"></i> '+
        '<span id="searchDateRange"></span>  '+
        '<b class="caret"></b></div> ';
    $('#mytoolbox').append(dataPlugin);
    //时间插件

    $('#reportrange').daterangepicker(
            {
                // startDate: moment().startOf('day'),
                //endDate: moment(),
                //minDate: '01/01/2012',    //最小时间
                maxDate : moment(), //最大时间
                dateLimit : {
                    days : 90
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

                $('#reportrange span').html(start.format('YYYY/MM/DD HH:mm:ss') + ' - ' + end.format('YYYY/MM/DD HH:mm:ss'));
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
        var table =  $("#tableList").DataTable();
        table.ajax.reload();
    });

    var searchBox = '{{$searchBox}}';
    searchBox = searchBox.replace(/&lt;/g, '<');
    searchBox = searchBox.replace(/&gt;/g, '>');
    searchBox = searchBox.replace(/&quot;/g, "\'");
    $('#searchbox').append(searchBox);
    
    var advanceSearchBox = '{{$advanceSearchBox}}'; 
    advanceSearchBox = advanceSearchBox.replace(/&lt;/g, '<');
    advanceSearchBox = advanceSearchBox.replace(/&gt;/g, '>');
    advanceSearchBox = advanceSearchBox.replace(/&quot;/g, "\'");
    $('#advanceSearchBox').append(advanceSearchBox);
    
    //点击搜索
    $("#searchSubmit").on('click',function(){
        var table =  $("#tableList").DataTable();
        table.ajax.reload();
    });
    
    $("#advanceSearchButton").on('click',function(){
        $("#advance_search_wrapper").slideToggle("fast");
    });

    //高级搜索
    $("#advanceSearchSubmit").on('click',function(){
        var table =  $("#tableList").DataTable();
        table.ajax.reload();
    });

}

</script>

@endsection
