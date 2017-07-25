@extends('layouts.app')

@section('fonts')
    <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
@endsection



@section('content')
        @if (Auth::check())
        	<div class="row text-center">
        		<div class=" col-xs-12 col-sm-3 repository-margin-bottom-1rem">
	                <a class="btn btn-default homepage-buttons" href="{{ route('incidents') }}">
	                	<span class="glyphicon glyphicon-info-sign">
	                		<div>
	                			Incidents
	                		</div>
	            		</span>
	                </a>
	            </div>

	            <div class=" col-xs-12 col-sm-3 repository-margin-bottom-1rem">
	                <a class="btn btn-default homepage-buttons">
	                	<span class="glyphicon glyphicon-calendar">
	                		<div>
	                			Schedule
	                		</div>
	            		</span>
	                </a>
	            </div>

	            <div class=" col-xs-12 col-sm-3 repository-margin-bottom-1rem">
	                <a class="btn btn-default homepage-buttons">
	                	<span class="glyphicon glyphicon-envelope">
	                		<div>
	                			City Email
	                		</div>
	            		</span>
	                </a>
	            </div>
            </div><!-- .row -->
        @else
            @include('auth.login');
        @endif
@endsection
