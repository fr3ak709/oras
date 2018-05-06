@extends('layouts.app')
@section('Scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js"></script>

@stop
@section('cardHeader')
    Prietaiso '<strong>{{$devices_name}}</strong>' sensoriai
    <a class='btn btn-info right'  href={{ url('devices') }}> Prietaisai     </a>
    <button  class='btn btn-info right'  data-toggle="modal" data-target="#myModal"> Pridėti sensorių</button>  
@stop
@section('content')
    <table style='margin: auto;'>
        <tr>
            <td style='width:20%'><strong>Pridėjimo data</strong></td>
            <td style='width:20%'><strong>Tinka vartoti iki</strong></td>
            <td style='width:30%'><strong>Pavadinimas</strong></td>
            <td style='width:30%'><strong>Matuojama</strong></td>
            <td></td>
        </tr>        
        @foreach ($devices_sensors as $item)
            @if( $item->needs_replacing )
                <tr class='table-row' style='background: #ff9bb7'>
            @else
                <tr class='table-row'>
            @endif
            
                <td>{{ $item->date }}</td>
                <td>{{ $item->valid_till }}</td>
                <td>{{ $item->name }}</td>
                <td>{{ $item->value_name }}</td>
                
                <td> 
                    <form action="{{ url('devices_sensor', $item->id ) }}" method="POST">
                        {{ csrf_field() }}
                        {{ method_field('DELETE') }}
                        <button class='btn btn-danger' onclick='return confirm("Ar tikrai norite pašalinti sensorių {{$item->name}} nuo prietaiso?")'>Pašalinti</button>
                    </form>
                </td>
            </tr>
        @endforeach
    </table>
    @include('devices/sensors/create')
@stop