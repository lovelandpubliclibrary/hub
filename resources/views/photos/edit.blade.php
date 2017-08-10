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

	<div class="panel panel-default">
		<div class="panel-heading text-center">
			<h2 class="panel-title">
				Edit a Picture
			</h2>
		</div> <!-- .panel-heading -->
		<div class="panel-body">
			<div class="well well-sm well-default">
				Photo of {{ ($photo->incident->patron_name ?: 'Unknown Patron') }}
				from 
				<a href="{{ route('incident', ['incident' => $photo->incident->id]) }}">
					{{ $photo->incident->title }}
				</a>
				on {{ $photo->incident->date }}
			</div>
			<div>
				<img class="img-responsive center-block" src="{{ asset('images/patrons/' . $photo->filename) }}" alt="Patron Picture">
			</div>
		</div><!-- .panel-body -->

		<div class="panel-footer">
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
									['class' => 'btn btn-default btn-success', 'type' => 'submit', 'title' => 'Save']) }}
					</div>
					
					{{ Form::hidden('user', Auth::user()->id) }}

				{{ Form::close() }}
			</div><!-- .panel-footer -->
	</div><!-- .panel -->
@endsection