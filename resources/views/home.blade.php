@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
			@include('includes.message')
			
			<!--Foreach para mostrar las imagenes que tenemos-->
			@foreach($images as $image)
				@include('includes.image',['image'=>$image])
			@endforeach

			
			<div class="clearfix"></div>

			<!--Mostrar los links -->
			{{$images->links()}}
        </div>

    </div>
</div>
@endsection
