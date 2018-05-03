@extends('layouts.app')
@section('cardHeader')
    Persisiūsti duomenis
@stop

@section('content')
    <form  action='/downloadCSV' method="post">
        @csrf
        <div class='columns'>
            <div class='column-left'>
                <div class="form-group row date">
                    <label for='date_from' class="col-md-6 col-form-label text-md-right">{{ __('Duomenis nuo') }}</label>
                    <div class='col-md-6'>
                        <input class='form-control' type='date' name='date_from' id='date_from' value={{date( "Y-m-d", strtotime("-7 days") )}} >
                    </div>
                </div>
                <div class="form-group row date">
                    <label for='date_to' class="col-md-6 col-form-label text-md-right">{{ __('Duomenis iki') }}</label>
                    <div class='col-md-6'>
                        <input class='form-control' type='date' name='date_to' id='date_to' value={{date("Y-m-d")}} >
                    </div>
                </div>
            </div>

            <div class='column-right'>
                @foreach($sensors as $sensor )
                    <div class="form-check radio col-md-12">
                        <label class="radio col-md-6 col-form-label text-md-right" for="sensors">{{$sensor->value_name . ' ' .$sensor->measuring_unit}}</label>
                        <input class="radio col-md-4 radio-circle" name="sensors" type="radio" value={{$sensor->value_name}} checked>
                    </div> <br />
                @endforeach
        </div>

        <div class="form-group row mb-0">
            <div class="col-md-6 offset-md-4">
                <button  class='btn btn-info' type='submit' name='button'>Persisiūsti duomenis</button>
            </div>
        </div>
    </form>
@stop