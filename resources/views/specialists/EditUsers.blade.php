@extends('layouts.app')
@section('Scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js"></script>
@stop
@section('cardHeader')
    Specialistai
    <button  class='btn btn-info right'  data-toggle="modal" data-target="#myModal"> Pridėti naują</button>
@stop
@section('content')
    <table style='margin: auto;'>
    <tr>
        <td style='width:20%'><strong>Pridėjimo data</strong></td>
        <td style='width:50%'><strong>Vardas pavardė</strong></td>
        <td style='width:100%'><strong>Email</strong></td>
        <td></td>
    </tr>
    @foreach ($users as $item)
        <tr class='table-row'>
            <td>{{ $item->created_at->format('Y-m-d') }}</td>
            <td>{{ $item->name }}</td>
            <td>{{ $item->email }}</td>
            <td> 
                <form action="{{ url('user' , $item->id ) }}" method="POST">
                    {{ csrf_field() }}
                    {{ method_field('DELETE') }}
                    <button class='btn btn-danger' onclick='return confirm("Ar tikrai norite pašalinti vartoją {{$item->email}}")'>Pašalinti</button>
                </form>
            </td>
        </tr>
    @endforeach

      
        <!-- Create specialist Modal  -->
    @include('specialists/create')
@stop
