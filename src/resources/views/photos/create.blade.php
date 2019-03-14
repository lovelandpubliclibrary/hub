@extends('layouts.app')

@section('content')
	<div id="photos">

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

		<div class="panel panel-default" id="photo">
			<div class="panel-heading col-xs-12 text-center repository-margin-bottom-1rem">
				<div class="col-xs-10 col-xs-offset-1">
					<h2 class="panel-title">
						Add a Photo
					</h2>
				</div>
			</div><!-- .panel-heading -->

			<div class="panel-body">
				@include('photos.partials.add_photo_form')
			</div><!-- .panel-body -->
		</div> <!-- .panel -->

	</div> <!-- #photos -->
  
@endsection