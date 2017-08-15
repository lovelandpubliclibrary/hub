@extends('layouts.app')

@section('fonts')
    <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
@endsection



@section('content')
        @if (Auth::check())
            <div class="col-xs-12 repository-margin-bottom-1rem">
                <a class="btn btn-default col-xs-12" href="{{ route('incidents') }}">
                	<div class="homepage-buttons repository-text-wrap">
	                	<span class="glyphicon glyphicon-info-sign"></span>
	                	Incidents
	                </div>
                	@if ($unviewed_incidents)
                        <div class="text-danger repository-text-wrap">
                            There {{ $unviewed_incidents > 1 ? 'are ' : 'is ' }}
                            {{ $unviewed_incidents }}
                            incident{{ $unviewed_incidents > 1 ? 's ' : '' }}
                            which require{{ $unviewed_incidents > 1 ? '' : 's' }} your review.
                        </div>
                    @else
                        <div class="text-success repository-text-wrap">
                            You've viewed all the incidents which have been reported!
                        </div>
                    @endif
                </a>
            </div>

            {{--
            <div class="col-xs-12 repository-margin-bottom-1rem">
                <a class="btn btn-default col-xs-12 homepage-buttons" href="{{ route('schedule') }}">
                	<span class="glyphicon glyphicon-calendar">
                		<div>
                			Schedule
                		</div>
            		</span>
                </a>
            </div>

            <div class="col-xs-12 repository-margin-bottom-1rem">
                <a class="btn btn-default col-xs-12 homepage-buttons" href="{{ route('cityemail') }}">
                	<span class="glyphicon glyphicon-envelope">
                		<div>
                			City Email
                		</div>
            		</span>
                </a>
            </div>
            --}}
        @else
            @include('auth.login')
        @endif
@endsection
