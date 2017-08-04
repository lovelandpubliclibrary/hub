@if (isset($breadcrumbs))
	<ul class="breadcrumb hidden-xs">
		@foreach ($breadcrumbs as $breadcrumb)
			<li>
				<a href="{{ $breadcrumb['link'] }}">
					{{ $breadcrumb['text'] }}
				</a>
			</li>
		@endforeach
	</ul>
@endif