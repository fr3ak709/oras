@extends('layouts.app')

@section('cardHeader')
    Ataskaitos
@stop

@section('content')
    <table style='margin: auto; width:80%;'>
        <tr>
            <td style='width:20%;' >Data</td>
            <td style='width:80%;'  >Pavadinimas</td>
        </tr>        
        @foreach ($reports as $item)
            <tr class='table-row'>
                <td ><a href={{ url('report' , $item->id )}}>{{ $item->date }}</a></td>
                <td ><a href={{ url('report' , $item->id )}}>{{ $item->title }}</a></td>
            </tr>
        @endforeach 
@stop