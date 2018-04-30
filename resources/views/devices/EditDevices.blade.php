@extends('layouts.app')
@section('cardHeader')
    Prietaisai
    <a class='btn btn-info right' href={{ url('newReport') }}> Pridėti naują </a>
@stop
@section('content')
       
    <table style='margin: auto;'>
        <tr>
            <td style='width:20%'><strong>Pavadinimas</strong></td>
            <td style='width:80%'><strong>Sensoriai</strong></td>
            <td></td>
            <td></td>
        </tr>        
        @foreach ($reports as $item)
            <tr class='table-row'>
                <td><a href={{ url('report' , $item->id ) }}>{{ $item->title }}</a></td>
                @foreach ($item->sensor as $sensor)
                    <td><a href={{ url('report', $item->id ) }}>{{ $sensor->title }}</a></td>
                @endforeach
                <td> 
                    <form action="{{ url('device', $item->id ) }}" method="POST">
                        {{ csrf_field() }}
                        {{ method_field('DELETE') }}
                        <button class='btn btn-danger' onclick='return confirm("Ar tikrai norite pašalinti prietaisą {{$item->title}}?")'>Pašalinti</button>
                    </form>
                </td>
                <td>
                    <form action="{{ url('device', $item->id ) }}" method="POST">
                        {{ csrf_field() }}
                        {{ method_field('UPDATE') }}
                        <button class='btn btn-danger'>Redaguoti</button>
                    </form>
                </td>
            </tr>
        @endforeach
    </table>
@stop