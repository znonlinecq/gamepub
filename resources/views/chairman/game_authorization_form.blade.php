@extends('dashboard')
@section('content')
@inject('Chairman', 'App\Models\Guild\GuildToGame')
{{ Form::open(array('url' => array($moduleRoute.'/game_authorization_form_submit'), 'method' => 'POST')) }}
<div class="box box-primary">
    <div class="box-header with-border">

     <a href="{{url($moduleRoute.'/game_authorization')}}" class="btn btn-default btn-sm active" role="button">返回</a>
&nbsp;&nbsp;
    <button type="submit" class="btn btn-primary btn-sm">提交</button>
       @if(session('message'))
        <p class="bg-success">{{session('message')}}</p>
        @endif    
    </div>
             <div class="box-body">
              <table id="example1" class="table ">
                <thead>
                <tr>
                  <th>游戏</th>
                  <th>分类</th>
                  <th>子类</th>
                  <th>介绍</th>
                  <th>授权</th>
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
                            <tr>
                                <td>{{$object->Gamename}}</td>
                                <td>{{$object->typeName}}</td>
                                <td>{{$object->className}}</td>
                                <td>{{$object->Brief}}</td>
                                   <td> <input type="checkbox" name="gids[]" value="{{$object->id}}" @if(App\Models\Guild\GuildToGame::check_game_authorization($chairman->Id, $object->id)) checked="true" @endif /></td>
                            </tr>
                        @endforeach
                  @endif  
                 </tbody>
              </table>
            </div>
            <!-- /.box-body -->
              <div class="box-footer">
                <button type="submit" class="btn btn-primary">提交</button>
              </div>
<input type="hidden" value="{{$chairman->Id}}" name="id" />
{!!Form::close()!!} 
</div>
@endsection
