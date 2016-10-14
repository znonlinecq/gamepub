@extends('dashboard')
@section('content')
<div class="box box-primary">
    <div class="box-header with-border">
        @if($tid)
            <a href="{{url($moduleRoute.'/types/classes/'.$tid)}}" class="btn btn-default btn-sm" >返回</a>
        @else
            <a href="{{url($moduleRoute.'/types/classes')}}" class="btn btn-default btn-sm" >返回</a>
        @endif

        @if(session('message'))
        <p class="bg-success">{{session('message')}}</p>
        @endif    
    </div>
{{ Form::open(array('url' => url($moduleRoute.'/types/classes_add_submit'), 'method' => 'POST')) }}
    <div class="box-body">
                <div class="form-group">
                  <label >分类名</label>
                  <input type="textfield" name="name" value="{{old('name')}}" class="form-control" >
                  @if($errors->has('name'))
                    {{$errors->first('name')}}
                  @endif 
                </div>
                <div class="form-group">
                   <label>选择类型</label>
                   <select name="tid" class="form-control" >
                        <option value=""> - 选择 - </option>
                    @foreach($types as $type)
                        <option value="{{$type->Typeid}}" @if($type->Typeid == $tid) selected="true" @endif >{{$type->Typename}}</option>
                    @endforeach
                    </select>
                  @if($errors->has('weight'))
                    {{$errors->first('weight')}}
                  @endif 
                </div>

                <div class="form-group">
                   <label>比重</label>
                   <input type="textfield" name="weight" value="{{old('weight')}}" class="form-control" >
                  @if($errors->has('weight'))
                    {{$errors->first('weight')}}
                  @endif 
                </div>
              <div class="box-footer">
                <button type="submit" class="btn btn-primary">提交</button>
              </div>
{!!Form::close()!!} 
         </div>
@endsection
