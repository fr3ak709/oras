@extends('layouts.app')
@section('Scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js"></script>
@stop
@section('cardHeader')
    Prietaisai
    <button  class='btn btn-info right'  data-toggle="modal" data-target="#myModal"> Pridėti prietaisą</button>
@stop
@section('content')
    <table style='margin: auto;'>
        <tr>
            <td style='width:20%'><strong>Pavadinimas</strong></td>
            <td style='width:80%'><strong>Sensoriai</strong></td>
            <td></td>
            <td></td>
        </tr>        
        @foreach ($devices as $item)
            <tr class='table-row'>
                <td><a href={{ url('device' , $item->id ) }}>{{ $item->name }}</a></td>
                <td>
                    <a href={{ url('device', $item->id ) }}>
                        @foreach ($item->sensors as $sensor)
                            @if ($sensor->needs_replacing)
                                <div style = 'color: #f47a9c'>
                            @else 
                                <div>
                            @endif
                            {{ $sensor->name }}
                            </div>
                        @endforeach
                    </a>
                </td>
                <td>
                    <form action="{{ url('device', $item->id ) }}">
                        <button class='btn btn-info'>Sensoriai</button>
                    </form>
                </td>
                <td> 
                    <form action="{{ url('device', $item->id ) }}" method="POST">
                        {{ csrf_field() }}
                        {{ method_field('DELETE') }}
                        <button class='btn btn-danger' onclick='return confirm("Ar tikrai norite pašalinti prietaisą {{$item->title}}?")'>Pašalinti</button>
                    </form>
                </td>

            </tr>
        @endforeach
    </table>
    @include('devices/create')
@stop