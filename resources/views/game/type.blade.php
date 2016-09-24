@extends('dashboard')
@section('content')
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
            <h3 class="box-title"><a href="{{url($moduleRoute.'/types_add')}}">添加</a></h3>
            @if(session('message'))
            <p class="bg-success">{{session('message')}}</p>
            @endif    
            <!-- /.box-header -->
           <div class="box-body">
 
              <table id="tableList" class="table table-bordered table-striped" data-page-length='25'  >
                <thead>
                <tr>
                  <th>ID</th>
                  <th>类型名称</th>
                  <th>下属分类</th>
                  <th>排序</th>
                  <th>操作</th>
                  <th>分类操作</th>
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
    var ajaxUrl = '/games/types_ajax';
    var delUrl = '/games/types_delete';

    if(host == 'localhost')
    {
       languageUrl = localUrl + languageUrl; 
       ajaxUrl = localUrl + ajaxUrl;
       delUrl = localUrl + delUrl;
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
                d.dateRange = $('#reportrange span').html();
            },
         headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
        },   
    });  
/*    table.on("click", ".delBtn", function(){
        var id = $(this).attr("data-object-id");
        $( "#dialog-confirm" ).dialog({
            resizable: false,
            height: "auto",
            width: 400,
            modal: true,
            buttons: {
            "删除": function() {
                $.ajax({
                    type: 'GET',
                    url: delUrl + '/' + id,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $( this ).dialog( "close" );
            },
                "取消": function() {
                    $( this ).dialog( "close" );
                }
        }
        }); 
}); */

});
</script>
<div id="dialog-confirm" style="display: none;" title="提示">
          <p><span class="ui-icon ui-icon-alert" style="float:left; margin:12px 12px 20px 0;"></span><br>您确定要执行此操作吗?</p>
</div>
@endsection
