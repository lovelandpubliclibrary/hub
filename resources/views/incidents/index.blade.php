@extends('layouts.app')

@section('content')
	<div class="content">
		<ul>
			@foreach($incidents as $incident)
				<li>{{ $incident}}</li>
			@endforeach
		</ul>
	</div>
@endsection