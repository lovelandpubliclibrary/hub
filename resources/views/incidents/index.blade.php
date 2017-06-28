@extends('layouts.app')

@section('content')
	<ul>
		@foreach($incidents as $incident)
			<li>{{ $incident}}</li>
		@endforeach
	</ul>
@endsection