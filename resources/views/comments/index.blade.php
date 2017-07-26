<ul class="list-group comments">
	@foreach ($comments as $comment)
		<li class="list-group-item">
			<div>
				<strong>
					{{ $comment->user->name }} commented on {{ $comment->created_at->toDayDateTimeString() }}:
				</strong>
			</div>
			<p>
				{{ $comment->comment }}
			</p>
		</li>
	@endforeach
</ul>
