@extends ('layouts.app')

@section ('content')
	<div id="reports">

		@include('layouts.breadcrumbs')

		<div class="panel panel-default">
			<div class="panel-heading col-xs-12 text-center repository-margin-bottom-1rem">
				<div class="col-xs-10 col-xs-offset-1">
					<h2 class="panel-title">
						Incident Reports
					</h2>
				</div>
			</div><!-- .panel-heading -->

			<div class="panel-body">
				<div class="col-xs-12 col-sm-6 report-item">
					<div class="bordered">
						<div class="text-center">
							<h3>
								Your Direct Reports
							</h3>
						</div>
						<table class="table table-striped table-condensed">
							<thead>
								<tr>
									<th>
										Name:
									</th>

									<th class="text-right">
										Unviewed Incidents:
									</th>
								</tr>
							</thead>

							<tbody>
								@foreach ($direct_reports as $direct_report)
									<tr @if ($direct_report->unviewedIncidents()->count() == 0) class="text-sucess" @endif>
										<td>
											{{ $direct_report->name }}
										</td>

										<td class="text-right">
				                            {{ $direct_report->unviewedIncidents()->count() }}
				                        </td>
									</tr>
								@endforeach
							</tbody>
						</table>
					</div> <!-- .bordered -->
				</div> <!-- .report-item -->

				<div class="col-xs-12 col-sm-6 report-item">
					<div class="bordered">
						<div class="text-center">
							<h3>
								Your Divisions
							</h3>
						</div>
						@foreach ($divisions as $division)
							<table class="table table-striped table-condensed">
								<comment>
									<h4 class="report-division-heading">
										{{ $division->division }}
									</h4>
								</comment>
																		
								<thead>
									<tr>
										<th>
											Name:
										</th>

										<th class="text-right">
											Unviewed Incidents:
										</th>
									</tr>
								</thead>

								<tbody>
									@foreach ($division->users as $user)
										<tr @if ($user->unviewedIncidents()->count() == 0) class="text-success" @endif>
											<td>
												{{ $user->name }}
											</td>

											<td class="text-right">
					                            {{ $user->unviewedIncidents()->count() }}
					                        </td>
										</tr>
									@endforeach
								</tbody>
							</table>
						@endforeach
					</div>
				</div>
			</div> <!-- .panel-body -->
		</div> <!-- .panel -->
	</div> <!-- #incidents -->
@endsection