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
<script src="{{ asset('resources/js/daterangeconfig.js') }}"></script>


<script>
$(function () {
    var host        = window.location.host;
    var languageUrl = '{{$languageUrl}}';
    var localUrl    = '{{$localUrl}}';
    var ajaxUrl     = '{{$moduleAjax}}';

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
        var dom = "<'row'<'col-sm-1'l><'col-sm-7'<'#mytoolbox' >><'col-sm-4'f>r>"+"t"+"<'row'<'col-sm-6'i><'col-sm-6'p>>"; 
    }
    else
    {
        var dom = "<'row'<'col-sm-1'l><'col-sm-7'><'col-sm-4'f>r>"+"t"+"<'row'<'col-sm-6'i><'col-sm-6'p>>"; 
;
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
</script>

@endsection
