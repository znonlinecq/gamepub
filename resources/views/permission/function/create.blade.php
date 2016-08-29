@extends('dashboard')
@section('content')
<div class="box box-primary">
    <div class="box-header with-border">
        @if($cid)
            <a href="{{url($moduleRoute.'/'.$cid)}}" class="btn btn-default btn-sm active" >返回</a>
        @else
            <a href="{{url($moduleRoute)}}" class="btn btn-default btn-sm active" >返回</a>
        @endif
        @if(session('message'))
        <p class="bg-success">{{session('message')}}</p>
        @endif    
    </div>
{{ Form::open(array('route' => array($moduleRoute.'.store'), 'method' => 'POST')) }}
    <div class="box-body">
                <div class="form-group">
                  <label >名称</label>
                  <input type="textfield" name="name" value="{{old('name')}}" class="form-control" >
                  @if($errors->has('name'))
                    {{$errors->first('name')}}
                  @endif 
                </div>
                <div class="form-group">
                  <label >模块</label>
                  <select class="form-control" name="cid" >
                    <option> - Select - </option>
                    @if(!empty($controllers))
                        @foreach($controllers as $controller)
                            @if($cid == $controller->id)
                              <option value="{{$controller->id}}" selected="true">{{$controller->name}}</option>
                            @else
                              <option value="{{$controller->id}}">{{$controller->name}}</option>
                            @endif      
                        @endforeach
                    @endif
                  </select>
                  @if($errors->has('cid'))
                    {{$errors->first('cid')}}
                  @endif 
                </div>
                 <div class="form-group">
                  <label >功能</label>
                  <input type="textfield" name="method" value="{{old('method')}}" class="form-control" >
                  @if($errors->has('method'))
                    {{$errors->first('method')}}
                  @endif 
                </div>
                <div class="form-group">
                   <label>比重</label>
                   <input type="textfield" name="weight" value="{{old('weight')}}" class="form-control" >
                  @if($errors->has('weight'))
                    {{$errors->first('weight')}}
                  @endif 
                </div>
               <div class="form-group">
                 {{ Form::label('title', '生成菜单') }}<br>
                 {{ Form::radio('menu', '1', true) }} 是 
                 {{ Form::radio('menu', '0') }} 否
                 @if($errors->has('menu'))
                    {{$errors->first('menu')}}
                  @endif 
                </div>
              <div class="form-group">
                {{ Form::label('title', '描述') }}<br>
               <textarea name="description" class="form-control" >{{old('description')}}</textarea>
                </div>
              <div class="box-footer">
                <button type="submit" class="btn btn-primary">提交</button>
              </div>
{!!Form::close()!!} 
         </div>
@endsection
