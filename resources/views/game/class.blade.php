@extends('dashboard')
@section('content')
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header"> 
            <a href="{{url($moduleRoute.'/types')}}" class="btn btn-default btn-sm active" >返回</a>
            &nbsp;&nbsp;
            
            <h3 class="box-title">
                @if($tid)
                    <a href="{{url($moduleRoute.'/types/classes_add/'.$tid)}}">添加</a>
                @else
                    <a href="{{url($moduleRoute.'/types/classes_add')}}">添加</a>
                @endif
            </h3>
            @if(session('message'))
            <p class="bg-success">{{session('message')}}</p>
            @endif    
            <!-- /.box-header -->
           <div class="box-body">
 
              <table id="tableList" class="table table-bordered table-striped" data-page-length='25'  >
                <thead>
                <tr>
                  <th>ID</th>
                  <th>分类名称</th>
                  <th>所属类型</th>
                  <th>排序</th>
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
<input type="hidden" value="{{$tid}}" id="tid" >
<!-- DataTables -->
<!-- CSS -->
<link rel="stylesheet" href="{{ asset('resources/plugins/daterangepicker/daterangepicker.css') }}">
<style>
    #mytoolbox {height:30px; line-height:30px;}
</style>

<!-- JS -->
<script src="{{ asset('resources/plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('resources/plugins/datatables/dataTables.bootstrap.min.js') }}"></script>
<script src="{{ asset('resources/plugins/daterangepicker/moment.js') }}"></script>
<script src="{{ asset('resources/plugins/daterangepicker/daterangepicker.js') }}"></script>
<script src="{{ asset('resources/js/daterangeconfig.js') }}"></script>
<script>
$(function () {
    var host = window.location.host;
    var languageUrl = '/chinese.json';
    var localUrl = 'http://localhost/gamepub/public';
    var ajaxUrl = '/games/types/classes_ajax';

    if(host == 'localhost')
    {
       languageUrl = localUrl + languageUrl; 
       ajaxUrl = localUrl + ajaxUrl;
    }

    var table =  $("#tableList").DataTable({
        paging: false,
        searching: false,
        info: false,
        order: [[3,'asc']],
        columns:[
            {"orderable":false},
            {"orderable":false},
            {"orderable":false},
            {"orderable":true},
            {"orderable":false},
        ],
        language: {
            url: languageUrl,
        },
        serverSide: true,   
        ajax: {
            url: ajaxUrl,
            type: 'POST',
            data: function (d){
                d.tid = $('#tid').val(),
                d.dateRange = $('#reportrange span').html();
            },
         headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
        },   
    });  

});
</script>
@endsection
