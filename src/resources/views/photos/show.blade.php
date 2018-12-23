@extends('layouts.app')

@section('content')
	@include('layouts.breadcrumbs')

	@if(Session::has('success_message'))
		<div class="alert alert-success">
			{{ Session::get('success_message') }}
		</div>
	@endif

	@if (count($errors) > 0)
		<div class="alert alert-danger">
			<ul>
				@foreach ($errors->all() as $error)
					<li>{{ $error }}</li>
				@endforeach
			</ul>
		</div>
	@endif

	<div class="panel panel-default">
		<div class="panel-heading col-xs-12 text-center repository-margin-bottom-1rem">
			<div class="center-block">
				<h2 class="panel-title">
					Photo #{{ $photo->id }}
				</h2>
			</div>

			{{-- Display the button to edit the photo if the user authored it or is an admin --}}
			@if (Auth::id() == $photo->user_id || Auth::user()->role->contains('role', 'Admin'))
				<div class="text-center-xs text-right-sm repository-margin-top-1rem">
					<a class="btn-sm btn-default link-default" href="/photos/edit/{{ $photo->id }}" title="Edit Photo">
						<span class="glyphicon glyphicon-edit"></span> Edit Photo
					</a>
				</div>
			@endif

		</div><!-- .panel-heading -->

		<div class="panel-body">
			<img class="img-responsive center-block" src="{{ asset('storage/photos/' . $photo->filename) }}" alt="Photo #{{ $photo->id }}">
			
			@if ($photo->caption)
				<div class="well well-sm text-center repository-margin-top-1rem">
					{{ $photo->caption }}
				</div>
			@endif
		</div><!-- .panel-body -->

	</div><!-- .panel -->
@endsection