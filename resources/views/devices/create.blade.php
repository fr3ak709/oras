@extends('layouts.app')
@section('cardHeader')
    Pridėti prietaisą
    <a class='btn btn-info right'  href={{ url('devices') }}> Prietaisai     </a>
@stop
@section('content')
    <form  action='/addDevice' method="post" >
        @csrf
        <div class='form-group'>
            <div class="form-group row">
                <label for='name'  class="col-md-4 col-form-label text-md-right">{{ __('Pavadinimas') }}</label>
                <div class='col-md-6'>
                    <input class='form-control' type='text' name='name' placeholder='Pavadinimas'>
                </div>
            </div>
            <div class="form-group row mb-0">
                <div class="col-md-6 offset-md-4">
                    <button  class='btn btn-info' type='submit' name='button'>Pridėti pritaisą</button>
                </div>
            </div>
        </div>
    </form>
@stop