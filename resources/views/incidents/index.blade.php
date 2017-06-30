@extends('layouts.app')

@section('content')
	<div class="container" style="margin-top: 4em;">
		<ul>
			@foreach($incidents as $incident)
				<li>{{ $incident}}</li>
			@endforeach
		</ul>
	</div>
@endsection