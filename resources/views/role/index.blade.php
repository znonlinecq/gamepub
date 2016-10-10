@extends('dashboard')
@section('content')
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
                  <th>名称</th>
                  <th>描述</th>
                  <th>创建时间</th>
                  <th>操作</th>
                </tr>
                </thead>
                <tbody>
                  @if(count($roles) === 0)
                      <tr>
                        <td> 没有数据 </td>
                        <td> </td>
                        <td>  </td>
                        <td>  </td>
                        <td>  </td>
                    </tr>
                  @else
                    @foreach($roles as $role)
                        <tr>
                            <td> {{$role->id}} </td>
                            <td>{{$role->name}}</td>
                            <td>{{$role->description}}</td>
                            <td>{{ date('Y-m-d H:i:s', $role->created) }}</td>
                            <td width="12%">
<!-- <a href="{{ url('roles/'.$role->id.'/edit') }}" class="btn btn-default" >编辑</a>
<button data-role-id="{{$role->id}}" href="#" class="btn btn-default btnDel" >删除</button>
-->
 <div class="row">
<div class="col-sm-6">
    <a href="{{ url('roles/'.$role->id.'/edit') }}" class="btn btn-default" >编辑</a>
</div>
<div class="col-sm-6">
{{ Form::open(array('method' => 'DELETE', 'id'=>'del-form-'.$role->id, 'route' => array('roles.destroy', $role->id))) }}
<input type="button" value="删除" data-role-id="{{$role->id}}" class="btn btn-default delBtn" />        
{{ Form::close() }}
</div>
</div>                 
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

    if(host == 'localhost')
    {
       languageUrl = localUrl + languageUrl; 
    }
    
    $("#example1").DataTable({
         language: {
            url: languageUrl,
            searchPlaceholder: '{{$searchPlaceholder}}',
        },
    
    });



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
