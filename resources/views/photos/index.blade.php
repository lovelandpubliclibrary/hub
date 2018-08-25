@extends ('layouts.app')

@section ('content')
	<div id="photos">

		@include('layouts.breadcrumbs')

		<div class="panel panel-default">
			<div class="panel-heading col-xs-12 text-center repository-margin-bottom-1rem">
				<div class="col-xs-10 col-xs-offset-1">
					<h2 class="panel-title">
						Photos
					</h2>
				</div>
			</div><!-- .panel-heading -->

			<div class="panel-body">
				<div class="col-xs-12">
					<a href="/photos/create" class="btn btn-danger col-xs-12 col-sm-3 repository-margin-bottom-1rem">
						Add a New Photo
					</a>

				</div>

				@if(count($photos))
					<table class="table table-striped table-condensed">
						<thead>
							<tr>
								<th class="text-nowrap hidden-xs">
									Photo Number
								</th>

								<th>
									Preview
								</th>

								<th class="text-nowrap">
									Caption
								</th>
							</tr>
						</thead>

						<tbody>
							@foreach ($photos as $photo)
								<tr>

									<td class="hub-center hidden-xs">
										{{ $photo->id }}
									</td>

									<td>
										<a href="{{ route('photo', ['photo' => $photo->id]) }}">
											<img class="img-responsive center-block" src="{{ asset('storage/photos/' . $photo->filename) }}" alt="Patro #{{ $photo->id }}">
										</a>
									</td>

									<td>
										{{ $photo->caption }}
									</td>

								</tr>
							@endforeach
						</tbody>
					</table>
					
				@else

					There are no photos to display.

				@endif

			</div> <!-- .panel-body -->

		</div> <!-- .panel -->

	</div> <!-- #incidents -->
@endsection