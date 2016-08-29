@extends('dashboard')
@section('content')
<section class="content">
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">
                <a href="{{url('modules')}}" class="btn btn-default">返回</a>&nbsp; 
                @if($cid)
                    <a href="{{url($moduleRoute.'/create/'.$cid)}}">添加</a>
                @else
                    <a href="{{url($moduleRoute.'/create')}}">添加</a>
                @endif
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
                  <th>模块</th>
                  <th>功能</th>
                  <th>比重</th>
                  <th>菜单</th>
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
                    </tr>
                  @else
                    @foreach($objects as $object)
                        <tr>
                            <td>{{$object->id}}</td>
                            <td>{{$object->name}}</td>
                            <td>{{$object->controller}}</td>
                            <td>{{$object->method}}</td>
                            <td>{{$object->weight}}</td>
                            <td>{{$object->menu}}</td>
                            <td width="12%">
 <div class="row">
<div class="col-sm-6">
    <a href="{{ url($moduleRoute.'/'.$object->id.'/edit') }}" class="btn btn-default" >编辑</a>
</div>
<div class="col-sm-6">
{{ Form::open(array('method' => 'DELETE', 'id'=>'del-form-'.$object->id, 'route' => array($moduleRoute.'.destroy', $object->id))) }}
<input type="button" value="删除" data-object-id="{{$object->id}}" class="btn btn-default delBtn" />        
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
        var id = $(this).attr("data-object-id");
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
