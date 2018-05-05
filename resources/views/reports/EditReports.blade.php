@extends('layouts.app')
@section('Scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js"></script>
@stop
@section('cardHeader')
    Jūsų ataskaitos
    <button  class='btn btn-info right'  data-toggle="modal" data-target="#myModal"> Pridėti ataskaitą</button>
@stop
@section('content')
   
    <table style='margin: auto;'>
        <tr>
            <td style='width:20%'><strong>Data</strong></td>
            <td style='width:80%'><strong>Pavadinimas</strong></td>
            <td></td>
        </tr>        
        @foreach ($reports as $item)
            <tr class='table-row'>
                <td><a href={{ url('report' , $item->id ) }}>{{ $item->date }}</a></td>
                <td><a href={{ url('report' , $item->id ) }}>{{ $item->title }}</a></td>
                <td> 
                    <form action="{{ url('report', $item->id ) }}" method="POST">
                        {{ csrf_field() }}
                        {{ method_field('DELETE') }}
                        <button class='btn btn-danger' onclick='return confirm("Ar tikrai norite pašalinti ataskaitą {{$item->title}}?")'>Pašalinti</button>
                    </form>
                </td>
            </tr>
        @endforeach
    </table>
    @include('reports/create')
@stop