@extends('layouts.app')
@section('cardHeader')
    Prietaisai
    <a class='btn btn-info right' href={{ url('newDevice') }}> Pridėti naują </a>
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
                    <form action="{{ url('device', $item->id ) }}" method="POST">
                        {{ csrf_field() }}
                        {{ method_field('DELETE') }}
                        <button class='btn btn-danger' onclick='return confirm("Ar tikrai norite pašalinti prietaisą {{$item->title}}?")'>Pašalinti</button>
                    </form>
                </td>
                <td>
                    <form action="{{ url('device', $item->id ) }}">
                        <button class='btn btn-danger'>Sensoriai</button>
                    </form>
                </td>
            </tr>
        @endforeach
    </table>
@stop