
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
            console.log(APP_URL);
            console.log('https://orotarsa.herokuapp.com/mapData/');
            $.ajax({
                //to-do max time between dates 1 day / 1 week / 1 month ?
                url: APP_URL+'/mapData',
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
            for (let i = 0; i < circles.length; i++) {
                if ( ( min_value/100 ) * max_value  >= circles[i].value ) {
                    circles[i].setMap(null);
                } else {
                    circles[i].setMap(map);
                }
            }
        });

    });

}