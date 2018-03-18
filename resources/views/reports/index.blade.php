@extends ('layouts.app')

@section ('content')
	<div id="reports">

		@include('layouts.breadcrumbs')

		<div class="panel panel-default">
			<div class="panel-heading col-xs-12 text-center repository-margin-bottom-1rem">
				<div class="col-xs-10 col-xs-offset-1">
					<h2 class="panel-title">
						Reports
					</h2>
				</div>
			</div><!-- .panel-heading -->

			<div class="panel-body">
				<div class="col-xs-12 repository-margin-bottom-1rem">
					<a class="btn btn-default col-xs-12" href="{{ route('reportIncidents') }}">
						<h2>Incident Report</h2>
		                <p>
		                	{{ $caught_up_count }} of {{ $supervises_count }} employees you supervise are caught up on Incidents.
		                </p>

		                <div class="progress">
							<div class="progress-bar {{ $bg_color }} "
								 role="progressbar"
								 aria-valuenow="{{ $percentage }}"
								 aria-valuemin="0"
								 aria-valuemax="{{ $supervises_count }}"
								 style="width:{{ $percentage }}%">
								 {{ $percentage }}%

							</div>
						</div>
		            </a>
	            </div>
			</div> <!-- .panel-body -->
		</div> <!-- .panel -->
	</div> <!-- #incidents -->
@endsection