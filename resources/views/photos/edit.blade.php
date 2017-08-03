@extends('layouts.app')

@section('content')
	@include('layouts.breadcrumbs')

	@if (count($errors) > 0)
		<div class="alert alert-danger">
			<ul>
				@foreach ($errors->all() as $error)
					<li>{{ $error }}</li>
				@endforeach
			</ul>
		</div>
	@endif

	<div class="h1 text-center">
		Edit a Photo
	</div>

	<div class="panel panel-default">
		<div class="panel-heading text-center">
			<h2 class="panel-title">
				Photo of {{ ($photo->incident->patron_name ?: 'Unknown Patron') }}
			</h2>

			<div>
				from 
				<a href="{{ route('incident', ['incident' => $photo->incident->id]) }}">
					{{ $photo->incident->title }}
				</a>
				on {{ $photo->incident->date }}
			</div>
		</div>
		<div class="panel-body">
			<img class="img-responsive" src="{{ asset('images/patrons/' . $photo->filename) }}" alt="Patron Picture">

			<div class="col-xs-12 panel-footer repository-margin-top-1rem">
				{{ Form::open(['action' => ['PhotoController@update', $photo->id]]) }}
					<div class="form-group">
						{{ Form::label('caption', 'Caption: ') }}
						{{ Form::text('caption', $photo->caption, ['class' => 'form-control']) }}
					</div>

					<div class="form-group text-right">
						<a class="text-danger repository-margin-right-1rem" href="{{ route('deletePhoto', ['photo' => $photo->id]) }}">
							<span class="glyphicon glyphicon-trash"></span> Delete
						</a>

						{{ Form::button('Save Changes',
									['class' => 'btn btn-default', 'type' => 'submit', 'title' => 'Save']) }}
					</div>
					
					{{ Form::hidden('user', Auth::user()->id) }}

				{{ Form::close() }}
			</div><!-- .panel-footer -->
		</div><!-- .panel-body -->
	</div><!-- .panel -->
@endsection