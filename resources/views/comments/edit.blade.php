@extends('layouts.app')

@section('content')
	<div id="#comments">
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
			Edit a Comment
		</div>

		{{ Form::open(['action' => 'CommentController@update']) }}
			<div class="panel panel-default">
				<div class="panel-body">
					<div class="form-group">
					    {{ Form::label('comment', 'Comment:') }}
					    {{ Form::textarea('comment', $comment->comment, [
		    				  'class' => 'form-control',
		    				  'required' => 'required',
		    				  'rows' => '5']) }}
				  	</div>

					{{ Form::hidden('user', Auth::id()) }}
					{{ Form::hidden('comment_id', $comment->id) }}

					<div class="panel-footer text-right repository-margin-top-1rem">
						<a class="text-danger repository-margin-right-1rem" href="{{ route('deleteComment', ['comment' => $comment->id]) }}">
							<span class="glyphicon glyphicon-trash"></span> Delete
						</a>
						{{ Form::button('Save Changes',
										['class' => 'btn btn-default btn-success', 'type' => 'submit', 'title' => 'Save']) }}
					</div>
				</div><!-- .panel-body -->
			</div><!-- .panel -->
		{{ Form::close() }}
	</div> <!-- #incidents -->
@endsection