@extends('layouts.app')
@section('cardHeader')
    Žemėlapis
@stop
@section('Styles')
    <link href="{{ secure_asset('/css/mapForm.css') }}" media="all" rel="stylesheet"  type="text/css">
@stop
@section('content')

    <br />
    <form>
        <div class='columns'>
            <div class='column-left'>
                <div class="form-group row date">
                    <label for='date_from' class="col-md-6 col-form-label text-md-right">{{ __('Duomenis nuo') }}</label>
                    <div class='col-md-6'>
                        <input class='form-control' type='date' id='date_from' value={{date( "Y-m-d", strtotime("-7 days") )}} >
                    </div>
                </div>
                <div class="form-group row date">
                    <label for='date_to' class="col-md-6 col-form-label text-md-right">{{ __('Duomenis iki') }}</label>
                    <div class='col-md-6'>
                        <input class='form-control' type='date' id='date_to' value={{date("Y-m-d")}} >
                    </div>
                </div>
                <div class="form-group row ">
                    <label for='min_value' class="col-md-6 col-form-label text-md-right">{{ __('Vertės viršijančios % leistino dydžio: ') }}</label>
                
                    <div class='col-md-4'>
                        <input class='form-control slider' type='range' id='min_value' value='0' >
                    </div>
                    <div class="col-md-2  text-md-right" id='min_value_text'></div>
                </div>
            </div>

            <div class='column-right'>
                <div class="form-group row radio-buttons">
                    @foreach($sensors as $sensor )
                    <div class="form-check radio col-md-12">
                        <label class="radio col-md-6 col-form-label text-md-right" for="sensors">{{$sensor->value_name . ' ' .$sensor->measuring_unit}}</label>
                        <input class="radio col-md-4 radio-circle" name="sensors" type="radio" value={{$sensor->value_name}} checked>
                    </div> <br />
                    @endforeach
                   
                </div>
            </div>
        </div>
        <button type='button' class='btn btn-submit' style='margin : 0 45% 5% 45%' id='apply_filter'>filtruoti </button>
    </form>
    <div id="map" class="map"></div>
    

    <script type="text/javascript">
        var APP_URL = {!! json_encode(url('/')) !!}
    </script>
    <script src="{{ secure_asset('/js/map.js') }}" >
        </script>
    <script src="{{ secure_asset('/js/range.js') }}" >
        </script>

    <script
        src="https://code.jquery.com/jquery-3.3.1.min.js"
        integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
        crossorigin="anonymous">
    </script>
    <script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBxGurAngBBEOYVVW1f--J9KtOlBF-yWtE&callback=initMap">
    </script>    
    


@stop