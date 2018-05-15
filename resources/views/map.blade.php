@extends('layouts.app')
@section('cardHeader')
<div style='display: flex'>
    <a  class='btn btn-info left' href="{{route('map')}}"   style='flex: 1; background: #0f7282; colour: #ffffff;' pressed> Žemėlapis</a>
    <a  class='btn btn-info right'href="{{route('graph')}}" style='flex: 1; colour: #ffffff;'> Grafikas</a>
</div>
@stop
@section('Styles')
    <link href="{{ secure_asset('/css/mapForm.css') }}" media="all" rel="stylesheet"  type="text/css">
@stop
@section('content')
        <h4 id='mapName'>Žemėlapis</h4>
        @include('dataFilter')
        <br><br>
        <div class="col-md-12 row">
            <label id='min_value_label' for='min_value' class="col-md-6 col-form-label text-md-right">
                Slėpti vertes mažesnes nei : 
                <label id='min_value_text'></label>
            </label>            
            <div class="col-md-6">
                <input class='slider' style='margin: 0.75rem 0 0 0' type='range' id='min_value' value='0' >
            </div>
        </div>

    <div class='legend'>
        <div>
            <div id='textGreen'>0</div>
            <div id='colourGreen' style='background: green'></div>
        </div>
        <div  >
            <div id='textYellow'>0</div>
            <div id='colourYellow' style='background: yellow'></div>
        </div> 
        <div >
            <div id='textRed'>0</div>
            <div id='colourRed' style='background: red'></div>
        </div>
        <div  >
            <div id='textBlack'>0</div>
            <div id='colourBlack' style='background: black'></div>
        </div>
    </div>

    <div id="map" class="map"></div>
    

    <script type="text/javascript">
        var APP_URL = {!! json_encode(url('/')) !!}
        var sensors = {!! json_encode($sensors) !!}
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