@extends('layouts.app')
@section('cardHeader')
    Dashboard
@stop
@section('content')
    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif

    <a href={{  url('generate_data') }}>generate data and come back</a>
    You are logged in!
@endsection
