@extends('layouts.app')

@section('fonts')
    <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
@endsection



@section('content')
        @if (Auth::check())
                Homepage info goes here.
        @else
            @include('auth.login');
        @endif
@endsection
