@extends('layouts.app')
@section('cardHeader')
    Grafikai
@stop
@section('Styles')
    <link href="{{ asset('css/mapForm.css') }}" rel="stylesheet">
@stop
@section('content')
    <div class='navGraphs'>
        <a href='' class='btn btn-info' >Žemėlapis</a> 
        <a href='' class='btn btn-info' >Grafikai</a> 
    </div>
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
                    <div class="form-check radio col-md-12">
                        <label class="radio col-md-6 col-form-label text-md-right" for="sensors">CO2</label>
                        <input class="radio col-md-4 radio-circle" name="sensors" type="radio" value='co2'>
                        
                    </div> <br />
                    <div class="form-check radio col-md-12">
                        <label class="radio col-md-6 col-form-label text-md-right" for="sensors">NO2</label>
                        <input class="radio col-md-4 radio-circle" name="sensors" type="radio" value='no2' checked>
                        
                    </div> <br />
                    <div class="form-check radio col-md-12">
                        <label class="radio col-md-6 col-form-label text-md-right" for="sensors">Temperature</label>
                        <input class="radio col-md-4 radio-circle" name="sensors" type="radio" value='temperature' >
                        
                    </div> <br />
                </div>
            </div>
        </div>
        <button type='button' class='btn btn-submit' style='margin : 0 45% 5% 45%' id='apply_filter'>filter </button>
    </form>
    <div id="map" class="map"></div>
    

    <script>
      function initMap() {
        var kaunas = new google.maps.LatLng( 54.90, 23.92);
        var circles = [];
        var map;
        var max_value = 0;

        map = new google.maps.Map(document.getElementById('map'), {
          zoom: 11,
          center: kaunas,
          mapTypeId: 'terrain'
        });

        $(document).ready(function(){
            sendRequest();      //loads the markers
            
            function sendRequest() {
                var date_from = document.getElementById("date_from").value;
                var date_to   = document.getElementById("date_to").value;
                var sensor    = $('input[name=sensors]:checked').val();
                $.ajax({
                    //to-do max time between dates 1 day / 1 week / 1 month ?
                    url: 'mapData/',
                    type: 'GET',
                    data: { 
                        date_from: date_from,
                        date_to:   date_to,
                        sensor: sensor,
                    },
                    success: function(response)
                    {
                        updateMap(response, sensor);
                    }
                });
            }

            function resetMap() {
                for (var i = 0; i < circles.length; i++) {
                    circles[i].setMap(null);
                    circles[i] = undefined;
                }
            }

            //puts markers on the map
            function updateMap(responseArray, sensor) {
                resetMap();
                for (let i = 0; i < responseArray.length; i++) {
                    max_value = 200;
                    var props = setProps(responseArray[i][sensor], max_value);
                    circles[i] = new google.maps.Circle({
                        value:  responseArray[i][sensor],
                        strokeWeight: 0,
                        fillColor: props.colour,
                        fillOpacity: props.opacity,
                        map: map,
                        center: new google.maps.LatLng(responseArray[i].lat, responseArray[i].long),
                        radius: 100,
                    });
                    //circle is the google.maps.Circle-instance
                    circles[i].addListener('mouseover',function(){
                        this.getMap().getDiv().setAttribute('title',(sensor+': '+Number(this.get('value')).toFixed(2)));
                    });

                    circles[i].addListener('mouseout',function(){
                        this.getMap().getDiv().removeAttribute('title');
                    });
                }
            }
            
            //assigns markers values on the map
            function setProps(value, valueMAX,
                opacityBlack= 1, opacityDark= 1, opacityLight= 1, opacityWhite= 1, 
                colourBlack='#000000', colourDark='#ff5e7e', colourLight='#f9ff8e', colourWhite='#8eff97'
            ) {
                if (value > valueMAX*0.95) 
                    return  {
                        colour: colourBlack,
                        opacity: opacityBlack,
                    };
                if (value > valueMAX*0.75)
                    return  {
                        colour: colourDark,
                        opacity: opacityDark,
                    };
                if (value > valueMAX*0.55)
                    return  {
                        colour: colourLight,
                        opacity: opacityLight,
                    };
                if (value)   
                    return {
                        colour: colourWhite,
                        opacity: opacityWhite,
                    };
                return {
                        colour: colourWhite,
                        opacity: 0,
                    };
            }

            $('#apply_filter').click(function(){
                sendRequest();
            });

            $('#min_value').change(function() {
                var min_value = document.getElementById("min_value").value;
                console.log('asd');
                for (let i = 0; i < circles.length; i++) {
                    console.log('asd');
                    if ( ( min_value/100 ) * max_value  >= circles[i].value ) {
                        circles[i].setMap(null);
                        console.log('asd');
                    } else {
                        circles[i].setMap(map);
                    }
                }
            });

        });

    }
    </script>
    <script>
        //fisplay sliders value in the form
        var slider = document.getElementById("min_value");
        var output = document.getElementById("min_value_text");
        output.innerHTML = slider.value + ' %'; // Display the default slider value

        // Update the current slider value (each time you drag the slider handle)
        slider.oninput = function() {
            output.innerHTML = this.value + ' %';
        }
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