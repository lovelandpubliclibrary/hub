@extends('layouts.app')

@section('content')
	<div id="staff">

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

		@if(Session::has('success_message'))
			<div class="alert alert-success">
				{{ Session::get('success_message') }}
			</div>
		@endif

		<div class="panel panel-default" id="patron">
			<div class="panel-heading col-xs-12 text-center repository-margin-bottom-1rem">
				<div class="col-xs-10 col-xs-offset-1">
					<h2 class="panel-title">
						Add a Staff Member
					</h2>
				</div>
			</div><!-- .panel-heading -->

			<div class="panel-body">
				@include('staff.partials.add_staff_form')
			</div><!-- .panel-body -->
		</div> <!-- .panel -->

	</div> <!-- #staff -->
  
@endsection