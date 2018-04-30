@extends('layouts.app')
@section('cardHeader')
    Sukurti ataskaitą
    <a class='btn btn-info right'  href={{ url('reports') }}> Ataskaitos     </a>
@stop

@section('content')
    <form  action='/uploadReport' method="post" enctype="multipart/form-data">
        @csrf
        <div class='form-group'>
            <div class="form-group row">
                <label for='title'  class="col-md-4 col-form-label text-md-right">{{ __('Pavadinimas') }}</label>
                <div class='col-md-6'>
                    <input class='form-control' type='text' name='title' placeholder='Pavadinimas'>
                </div>
            </div>
            <div class="form-group row">
                <label for='Date' class="col-md-4 col-form-label text-md-right">{{ __('Data') }}</label>
                <div class='col-md-6'>
                    <input class='form-control' type='date' name='date' value={{date("Y-m-d")}} >
                </div>
            </div>
            <div class="form-group row">
                <label for='Date' class="col-md-4 col-form-label text-md-right">{{ __('Failas') }}</label>
                <div class='col-md-6 '>
                    <input class='form-control-file' type="file" accept='application/pdf' name='report' value=''>
                </div>
            </div>
            <div class="form-group row mb-0">
                <div class="col-md-6 offset-md-4">
                    <button  class='btn btn-info' type='submit' name='button'>Upload a report</button>
                </div>
            </div>
        </div>
    </form>
@stop