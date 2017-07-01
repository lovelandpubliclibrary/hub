@extends('layouts.app')

@section('content')
	<div class="h1">
		{{ $incident->title }}
	</div>

	<div>
		Date of Incident:
		{{ $incident->date }}
	</div>

	<div>
		Patron Name:
		@isset($incident->patron_name)
			{{ $incident->patron_name }}
		@else
			Unknown
		@endisset
	</div>

	<div>
		{{ $incident->description }}
	</div>
@endsection