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
              <table data-page-length='25' id="tableList" class="table table-striped">
                <thead>
                <tr>
                  <th>时间</th>
                  <th>操作人</th>
                  <th>操作</th>
                  <th>对象</th>
                  <th>描述</th>
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
<!-- page script -->
<script>
$(function () {
    var host = window.location.host;
    var languageUrl = '/chinese.json';
    var localUrl = 'http://localhost/gamepub/public';
    
    var controllerType ='{{$controllerType}}';
    var methodType ='{{$methodType}}';
    var ajaxUrl = '/logs/index_ajax';

    if(host == 'localhost')
    {
       languageUrl = localUrl + languageUrl; 
       ajaxUrl = localUrl + ajaxUrl;
    }
    var table =  $("#tableList").DataTable({
        order: [[0,'desc']],
        columns:[
            {"orderable":true},
            {"orderable":false},
            {"orderable":false},
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
            data: {"controllerType": controllerType, "methodType":methodType},
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
        },
    });

});
</script>

@endsection
