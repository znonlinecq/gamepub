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
    var ajaxUrl = '/chairmans/blacklist_ajax';
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
            searchPlaceholder: '{{$searchPlaceholder}}',
        },
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
        "dom":"<'row'<'col-sm-1'l><'col-sm-7'<'#mytoolbox' >><'col-sm-4'f>r>"+"t"+"<'row'<'col-sm-6'i><'col-sm-6'p>>",
        initComplete:initComplete,
    });
});
</script>

@endsection
