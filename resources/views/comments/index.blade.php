<ul class="list-group comments">
	@foreach ($comments as $comment)
		<li class="list-group-item">
			<div>
				<strong>
					{{ $comment->user->name }} commented on {{ $comment->created_at->toDayDateTimeString() }}:
				</strong>

				@if ($comment->updated_at > $comment->created_at)
					<span class="glyphicon glyphicon-exclamation-sign text-info"
						  title="This comment was updated on {{ $comment->updated_at->toDayDateTimeString() }}"></span>
				@endif
			</div>

			<div>
				{{ $comment->comment }}
			</div>

			{{-- Display the button to edit the comment if the user authored it --}}
			@if (Auth::id() == $comment->user_id)
				<div class="text-center-xs text-right-sm repository-margin-top-1rem">
					<a class="btn-sm btn-default link-default" href="{{ route('editComment', ['comment' => $comment->id]) }}" title="Edit Comment">
						<span class="glyphicon glyphicon-edit"></span> Edit Comment
					</a>
				</div>
			@endif

		</li>
	@endforeach
</ul>
