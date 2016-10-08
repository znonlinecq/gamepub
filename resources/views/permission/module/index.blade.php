@extends('dashboard')
@section('content')
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">
              <a href="{{url($moduleRoute.'/create')}}">添加</a>
              </h3>
            @if(session('message'))
            <p class="bg-success">{{session('message')}}</p>
            @endif    
            <!-- /.box-header -->
            <div class="box-body">
              <table id="example1" class="table table-bordered table-striped" data-page-length='25'>
                <thead>
                <tr>
                  <th>ID</th>
                  <th>名称</th>
                  <th>包含功能(个)</th>
                  <th>排序</th>
                  <th>菜单</th>
                  <th>操作</th>
                  <th>功能操作</th>
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
                            <td>{{$object->functions}}</td>
                            <td>{{$object->weight}}</td>
                            <td>{{$object->menu}}</td>
                        <td width="15%">
                            <div class="row">
                                <div class="col-sm-5">
    <a href="{{ url($moduleRoute.'/'.$object->id.'/edit') }}" class="btn btn-default" >编辑</a>
                                </div>
                                <div class="col-sm-4">
{{ Form::open(array('method' => 'DELETE', 'id'=>'del-form-'.$object->id, 'route' => array($moduleRoute.'.destroy', $object->id))) }}
<input type="button" value="删除" data-object-id="{{$object->id}}" class="btn btn-default delBtn" />        
{{ Form::close() }}
                                </div>
                            </div>
                        </td>  
                        <td width="15%">
                            <div class="row">
                                <div class="col-sm-4">
    <a href="{{ url('functions/'.$object->id) }}" class="btn btn-default" >查看</a>
                                </div>
                                <div class="col-sm-4">
    <a href="{{ url('functions/create/'.$object->id) }}" class="btn btn-default" >添加功能</a>
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
