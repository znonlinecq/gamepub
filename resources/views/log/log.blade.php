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
              <table id="example1" class="table table-striped">
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
                  @if(count($objects) === 0)
                      <tr>
                        <td> 没有数据 </td>
                        <td> </td>
                        <td>  </td>
                        <td>  </td>
                        <td>  </td>
                    </tr>
                  @else
                    @foreach($objects as $object)
                        <tr>
                            <td width="15%">{{$object->created}}</td>
                            <td>{{$object->operator}}</td>
                            <td>{{$object->operation}}</td>
                            <td>{{$object->person}}</td>
                            <td width="60%">{{$object->content}}</td>
                        </tr>
                    @endforeach
                  @endif  
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
    $("#example1").DataTable();

    $(".delBtn").click(function(){
        var id = $(this).attr("data-role-id");
        $( "#dialog-confirm" ).dialog({
            resizable: false,
            height: "auto",
            width: 400,
            modal: true,
            buttons: {
            "删除": function() {
                $("#del-form-"+id).submit();            
                $( this ).dialog( "close" );
            },
                "取消": function() {
                    $( this ).dialog( "close" );
                }
        }
        });
    });

});
</script>

<div id="dialog-confirm" style="display: none;" title="提示">
          <p><span class="ui-icon ui-icon-alert" style="float:left; margin:12px 12px 20px 0;"></span><br>您确定要执行此操作吗?</p>
</div>
@endsection
