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
		{{-- check if the user it at a top-level page and modify the breadcrumb content accordingly --}}
		@if (preg_match('/' . Request::segment(1) . '\/?$/', url()->current()))
			<a href="{{ route('home') }}">
				<< Back to Home
			</a>
		@else
			@php
				switch (Request::segment(1)) {
					// route children of incidents to the incident index
					case 'photos':
					case 'comments':
						echo '<a href="' . route('incidents') . '">' .
							 '<< Back to Incidents' .
							 '</a>';
						break;
					// route all other navigation to the parent resource based on the URL requested
					default:
						echo '<a href="' . route(Request::segment(1)) . '">' .
							 '<< Back to ' . ucwords(Request::segment(1)) .
							 '</a>';
						break;
				}
			@endphp
		@endif
	</div>

@endif