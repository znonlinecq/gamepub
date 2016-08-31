@extends('dashboard')
@section('content')
@inject('Permission', 'App\Models\Permission')
<div class="box box-primary">
    <div class="box-header with-border">
        @if(session('message'))
        <p class="bg-success">{{session('message')}}</p>
        @endif    
    </div>
{{ Form::open(array('route' => array($moduleRoute.'.store'), 'method' => 'POST')) }}
             <div class="box-body">
              <table id="example1" class="table ">
                <thead>
                <tr>
                  <th>权限</th>
                  @foreach($roles as $role)
                        <th>{{$role->name}}</th>
                  @endforeach
                </tr>
                </thead>
                <tbody>
                  @if(count($objects) === 0)
                      <tr>
                        <td> 没有数据 </td>
                        <td> </td>
                    </tr>
                  @else
                    @foreach($objects as $object)
                        <tr class="active">
                            <td>{{$object->name}}</td>
                             @foreach($roles as $role)
                                <th></th>
                             @endforeach
                        </tr>
                        @foreach($object->functions as $function)
                            <tr>
                                <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{$function->name}}</td>
                                @foreach($roles as $role)
                                   <td> <input type="checkbox" name="permissions[]" value="{{$role->id}}-{{$object->id}}-{{$function->id}}" @if(App\Models\Permission::check_exist($role->id, $object->id, $function->id)) checked="true" @endif /></td>
                                @endforeach
                            </td>
                        @endforeach
                    @endforeach
                  @endif  
                 </tbody>
              </table>
            </div>
            <!-- /.box-body -->
              <div class="box-footer">
                <button type="submit" class="btn btn-primary">提交</button>
              </div>
{!!Form::close()!!} 
</div>
@endsection
