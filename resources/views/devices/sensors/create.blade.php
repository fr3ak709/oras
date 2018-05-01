@extends('layouts.app')
@section('cardHeader')
    Pridėti sensorių
    <a class='btn btn-info right'  href={{ url('devices') }}> Prietaisai</a>
@stop
@section('content')

    <form  action={{'/addSensor/'.$devices_id}} method="post" enctype="multipart/form-data">
        @csrf
        <div class='form-group'>
            <div class="form-group row">
                <label for='title'  class="col-md-4 col-form-label text-md-right">{{ __('Sensorius') }}</label>
                <div class='col-md-6'>
                <select class='form-control' name='sensors_id'>
                    @foreach($sensors as $sensor)
                        <option value={{$sensor->id}} >{{ $sensor->name . ' [' .$sensor->value_name.']'  }}</option>
                    @endforeach
                </select>
                </div>
            </div>
            <div class="form-group row">
                <label for='Date' class="col-md-4 col-form-label text-md-right">{{ __('Data') }}</label>
                <div class='col-md-6'>
                    <input class='form-control' type='date' name='date' value={{date("Y-m-d")}} >
                </div>
            </div>
            <div class="form-group row mb-0">
                <div class="col-md-6 offset-md-4">
                    <button  class='btn btn-info' type='submit' name='button'>Pridėti sensorių</button>
                </div>
            </div>
        </div>
    </form>
    
@stop