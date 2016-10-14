@extends('dashboard')
@section('content')
<div class="box box-primary">
    <div class="box-header with-border">
        <a href="{{url('users')}}" class="btn btn-default btn-sm" >返回</a>
        @if(session('message'))
        <p class="bg-success">{{session('message')}}</p>
        @endif    
    </div>
{{ Form::open(array('route' => array('users.store'), 'method' => 'POST')) }}
    <div class="box-body">
                <div class="form-group">
                  <label >用户名</label>
                  <input type="textfield" name="name" value="{{old('name')}}" class="form-control" placeholder="Enter name">
                  @if($errors->has('name'))
                    {{$errors->first('name')}}
                  @endif 
                </div>
                <div class="form-group">
                  <label >Email</label>
                  <input type="email" name="email" value="{{old('email')}}" class="form-control" >
                  @if($errors->has('email'))
                    {{$errors->first('email')}}
                  @endif 
                </div>
                <div class="form-group">
                  <label>角色</label>
                  <select class="form-control" name="role" >
                    <option> - Select - </option>
                    @if(!empty($roles))
                        @foreach($roles as $role)
                              <option value="{{$role->id}}">{{$role->name}}</option>
                        @endforeach
                    @endif
                  </select>
                </div>
                <div class="form-group">
                  <label>状态</label>
                  <select class="form-control" name="status">
                    <option> - Select - </option>
                        <option value="1">正常</option>
                        <option value="2">停止</option>
                  </select>
                </div>
      <div class="form-group has-feedback">
        <label>密码</label>
        <input type="password" name="password" class="form-control" placeholder="Password">
        @if($errors->has('password'))
           {{$errors->first('password')}}
        @endif 
        
      </div>
      <div class="form-group has-feedback">
        <label>重复密码</label>
        <input type="password" name="password_confirmation" class="form-control" placeholder="Retype password">
      </div>

              <div class="box-footer">
                <button type="submit" class="btn btn-primary">提交</button>
              </div>
{!!Form::close()!!} 
         </div>
@endsection
