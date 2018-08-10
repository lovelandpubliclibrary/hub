<ul class="list-group comments">
	@foreach ($comments as $comment)
		<li class="list-group-item">
			<div class="comment-wrapper">
				<strong>
					@if (Auth::id() == $comment->user_id)
						You 
					@else
						{{ $comment->user->name }} 
					@endif

					commented on {{ $comment->created_at->toDayDateTimeString() }}:
				</strong>

				{{-- Indicate when a comment has been updated --}}
				@if ($comment->updated_at > $comment->created_at)
					<span class="glyphicon glyphicon-exclamation-sign text-info"
						  title="This comment was updated on {{ $comment->updated_at->toDayDateTimeString() }}"></span>
				@endif

				{{-- Display the button to edit the comment if the user authored it --}}
				@if (Auth::id() == $comment->user_id)
					<a class="btn btn-sm btn-default link-default" href="{{ route('editComment', ['comment' => $comment->id]) }}" title="Edit Comment">
						<span class="glyphicon glyphicon-edit"></span> Edit
					</a>
				@endif

				<div>
					{{ $comment->comment }}
				</div>

			</div>	{{-- .comment-wrapper --}}

		</li>
	@endforeach
</ul>
