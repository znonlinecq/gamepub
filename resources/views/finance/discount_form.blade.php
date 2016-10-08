@extends('dashboard')
@section('content')
@inject('Permission', 'App\Models\Permission')
<div class="box box-primary">
    <div class="box-header with-border">
        @if(session('message'))
        <p class="bg-success">{{session('message')}}</p>
        @endif    
    </div>
{{ Form::open(array('url' => url($moduleRoute.'/discount_form_submit'), 'method' => 'POST')) }}
             <div class="box-body">
              <table id="example1" class="table ">
                <thead>
                <tr>
                    <th>月份</th>
                    <th>A级公会</th>
                    <th>B级公会</th>
                </tr>
                </thead>
                <tbody>
                  @if(count($months) === 0)
                      <tr>
                        <td> 没有数据 </td>
                        <td> </td>
                    </tr>
                  @else
                        @foreach($months as $key => $value)
                            <tr>
                                <td>{{$value}}</td>
                                   <td> <input class="form-control" type="textfield" name="a[]" value="{{$guild_a_discount[$key]}}" /></td>
                                   <td> <input class="form-control" type="textfield" name="b[]" value="{{$guild_b_discount[$key]}}" /></td>
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
