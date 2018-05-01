@extends('layouts.app')
@section('cardHeader')
    Valdyti prietaiso sensorius
    <a class='btn btn-info right'  href={{ url('devices') }}> Prietaisai     </a>
    <a class='btn btn-info right'  href={{ url('newSensor', $devices_id) }}> Pridėti Naują     </a>
    
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
        @foreach ($sensors as $item)
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
@stop