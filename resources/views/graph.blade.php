@extends('layouts.app')
@section('cardHeader')
<div style='display: flex'>
    <a  class='btn btn-info left' href="{{route('map')}}"   style='flex: 1; colour: #ffffff;' pressed> Žemėlapis</a>
    <a  class='btn btn-info right'href="{{route('graph')}}" style='flex: 1; background: #0f7282; colour: #ffffff;'> Grafikas</a>
</div>
@stop
@section('Styles')
    <link href="{{ secure_asset('/css/mapForm.css') }}" media="all" rel="stylesheet"  type="text/css">
@stop
@section('content')
  
    <h4 id='graphName'>Grafikas</h4>
    @include('dataFilter')
    <canvas id='myChart'>
    </canvas>

    <script
        src="https://code.jquery.com/jquery-3.3.1.min.js"
        integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
        crossorigin="anonymous">
    </script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.6.0/Chart.min.js'>
    </script>
        <script type="text/javascript">
        var APP_URL = {!! json_encode(url('/')) !!}
        var sensors = {!! json_encode($sensors) !!}
    </script>
    <script src="{{ secure_asset('/js/chart.js') }}" >
        </script>


@stop