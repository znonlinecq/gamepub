@extends('dashboard')
@section('content')
<div class="box box-primary">
    <div class="box-header with-border">
        <a href="{{url($moduleRoute)}}" class="btn btn-default btn-sm" >返回</a>
        @if(session('message'))
        <p class="bg-success">{{session('message')}}</p>
        @endif    
    </div>
{{ Form::open(array('route' => array($moduleRoute.'.update', $object->id), 'method' => 'PUT')) }}
    <div class="box-body">
                <div class="form-group">
                  <label >模块名</label>
                  <input type="textfield" name="name" value="{{$object->name}}" class="form-control" >
                  @if($errors->has('name'))
                    {{$errors->first('name')}}
                  @endif 
                </div>
                <div class="form-group">
                  <label >控制器</label>
                  <input type="textfield" name="controller" value="{{$object->controller}}" class="form-control" >
                  @if($errors->has('controller'))
                    {{$errors->first('controller')}}
                  @endif 
                </div>
                <div class="form-group">
                   <label>比重</label>
                   <input type="textfield" name="weight" value="{{$object->weight}}" class="form-control" >
                  @if($errors->has('weight'))
                    {{$errors->first('weight')}}
                  @endif 
                </div>
               <div class="form-group">
                 {{ Form::label('title', '生成菜单') }}<br>
                 @if($object->menu == 1)
                    {{ Form::radio('menu', '1', true) }} 是 
                    {{ Form::radio('menu', '0') }} 否
                 @else
                    {{ Form::radio('menu', '1')}} 是 
                    {{ Form::radio('menu', '0', true) }} 否 
                 @endif

                 @if($errors->has('menu'))
                    {{$errors->first('menu')}}
                  @endif 
                </div>
              <div class="form-group">
                {{ Form::label('title', '描述') }}<br>
               <textarea name="description" class="form-control" >{{$object->description}}</textarea>
                </div>
              <div class="box-footer">
                <button type="submit" class="btn btn-primary">提交</button>
              </div>
{!!Form::close()!!} 
         </div>
@endsection
