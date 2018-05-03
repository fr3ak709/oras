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

    Jūs prisijungėte
@endsection
