@extends('dashboard')
@section('content')
<section class="content">
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">
              <a href="{{url('roles/create')}}">角色添加</a>
              </h3>
            @if(session('message'))
            <p class="bg-success">{{session('message')}}</p>
            @endif    
            <!-- /.box-header -->
            <div class="box-body">
              <table id="example1" class="table table-bordered table-striped">
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
                  <th>操作</th>
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
                        <td>  </td>
                        <td>  </td>
                        <td>  </td>
                        <td>  </td>
                    </tr>
                  @else
                    @foreach($objects as $object)
                        <tr>
                            <td>{{$object->id}}</td>
                            <td>{{$object->acount}}</td>
                            <td>{{$object->guildid}}</td>
                            <td>{{$object->games}}</td>
                            <td>{{$object->name}}</td>
                            <td>{{$object->namecard}}</td>
                            <td>{{$object->qq}}</td>
                            <td>{{$object->created}}</td>
                            <td width="12%">
                                <a href="{{url('chairmans/audit_form')}}">审核</a>
                            </td>
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
    </section>
    <!-- /.content -->
  </div>
 
</section>
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
