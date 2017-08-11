@if (isset($breadcrumbs))
	{{-- display full breadcrumbs for larger screens --}}
	<ul class="breadcrumb hidden-xs">
		@foreach ($breadcrumbs as $breadcrumb)
			<li>
				<a href="{{ $breadcrumb['link'] }}">
					{{ $breadcrumb['text'] }}
				</a>
			</li>
		@endforeach
	</ul>

	{{-- display simple breadcrumbs for mobile screens --}}
	<div class="breadcrumb visible-xs">
		@if (!preg_match('/' . Request::segment(1) . '\/?$/', url()->current()))
			<a href="{{ route(Request::segment(1)) }}">
				<< Back to {{ ucwords(Request::segment(1)) }}
			</a>
		@else
			<a href="{{ route('home') }}">
				<< Back to Home
			</a>
		@endif
	</div>

@endif