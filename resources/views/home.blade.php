@extends('layouts.app')

@section('fonts')
    <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
@endsection



@section('content')
        
    @if (Session::has('success_message'))
        <div class="alert alert-success">
            {{ Session::get('success_message') }}
        </div>
    @endif

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
                        There are no incidents which require your review.
                    </div>
                @endif
            </a>
        </div>

            @if (Auth::user()->hasRole($supervisor_role))
                <div class="col-xs-12 repository-margin-bottom-1rem">
                    <a class="btn btn-default col-xs-12" href="{{ route('reports') }}">
                        <div class="homepage-buttons repository-text-wrap">
                            <span class="glyphicon glyphicon-list-alt"></span>
                            Reports
                        </div>
                    </a>
                </div>
            @endif

        @else
            @include('auth.login')
        @endif
@endsection
