@extends('dashboard')

@section('content')

<div class="container" style="width:100%">
<div class="col-md-12">
<div class="row">
@foreach($objects as $object)
<blockquote>
<p>{{$object['title']}}</p>
<footer>{{$object['content']}}</footer>
</blockquote>
@endforeach
</div>

</div>
</div>
@endsection
