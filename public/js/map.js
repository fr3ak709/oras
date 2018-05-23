
function initMap() {
    var kaunas = new google.maps.LatLng( 54.90, 23.92);
    var circles = [], map;
    var value_max, value_name,  measuring_unit ;    
    var min_value = document.getElementById("min_value");
    var min_value_text = document.getElementById("min_value_text");
    var date_from ,  date_to; 

    var colourBlack='#000000', colourRed='#ff5e7e', colourYellow='#f9ff8e', colourGreen='#8eff97';
    var startBlack=1, startRed=0.9, startYellow=0.75;

    map = new google.maps.Map(document.getElementById('map'), {
        zoom: 11,
        center: kaunas,
        mapTypeId: 'terrain'
    });


    $(document).ready(function(){
        sendRequest();      //loads the markers
        setLegend();
        setMapName();
        resetRange();
        function sendRequest() {
            sensor    = $('input[name=sensors]:checked').val();
            date_from = document.getElementById("date_from").value;
            date_to   = document.getElementById("date_to").value;
            sensors.forEach(element => {
                if(element.value_name == sensor) {
                    value_name = element.value_name;
                    value_max = element.value_max;
                    measuring_unit = element.measuring_unit;
                }
            }); 

            String.prototype.splice = function(idx, rem, str) {
                return this.slice(0, idx) + str + this.slice(idx + Math.abs(rem));
            };
            $.ajax({
                //to-do max time between dates 1 day / 1 week / 1 month ?
                url: APP_URL.splice(4, 0, "")+'/data',
                type: 'GET',
                data: { 
                    date_from: date_from,
                    date_to:   date_to,
                    sensor: sensor,
                },
                success: function(response)
                {
                    updateMap(response);
                }
            });
        }
        
        function resetMap() {
            for (var i = 0; i < circles.length; i++) {
                circles[i].setMap(null);
            }
        }

        //puts markers on the map
        function updateMap(responseArray) {
            resetMap();
            circles = [];
            for (let i = 0; i < responseArray.length; i++) {
                var props = setProps(responseArray[i].value);
                circles[i] = new google.maps.Circle({
                    value:  responseArray[i].value,
                    date: responseArray[i].date,
                    strokeWeight: 0,
                    fillColor: props.colour,
                    fillOpacity: props.opacity,
                    map: map,
                    center: new google.maps.LatLng(responseArray[i].lat, responseArray[i].long),
                    radius: 100,
                });


                //circle is the google.maps.Circle-instance
                circles[i].addListener('mouseover',function(){
                    this.getMap().getDiv().setAttribute('title',('DATA:'+this.get('date')+' [vertė:'+Number(this.get('value')).toFixed(2)+measuring_unit+']'));
                });

                circles[i].addListener('mouseout',function(){
                    this.getMap().getDiv().removeAttribute('title');
                });
            }
        }

        function setLegend () {
            
            var textBlackElement = document.getElementById("textBlack");
            var textRedElement = document.getElementById("textRed");
            var textYellowElement = document.getElementById("textYellow");
            var textGreenElement = document.getElementById("textGreen");
            var colourBlackElement = document.getElementById("colourBlack");
            var colourRedElement = document.getElementById("colourRed");
            var colourYellowElement = document.getElementById("colourYellow");
            var colourGreenElement = document.getElementById("colourGreen");
            colourBlackElement.style.backgroundColor=colourBlack;
            colourRedElement.style.backgroundColor=colourRed;
            colourYellowElement.style.backgroundColor=colourYellow;
            colourGreenElement.style.backgroundColor=colourGreen;
            textBlackElement.innerHTML      = 'virš '+ value_max + ' ' + measuring_unit;
            textRedElement.innerHTML        = 'iki ' + value_max*startBlack + ' ' + measuring_unit;
            textYellowElement.innerHTML   = 'iki ' + value_max*startRed + ' ' + measuring_unit;
            textGreenElement.innerHTML    = 'iki ' + value_max*startYellow + ' ' + measuring_unit;
        }
        function setMapName () {
            document.getElementById("mapName").innerHTML = 'Žemėlapis \''+value_name + '\' nuo: '+date_from + ' iki: '+date_to;
        }
        //assigns markers values on the map
        function setProps(value, 
            opacityBlack= 1, opacityDark= 1, opacityLight= 1, opacityWhite= 1, 
            
        ) {
            if (value > value_max*startBlack) 
                return  {
                    colour: colourBlack,
                    opacity: opacityBlack,
                };
            if (value > value_max*startRed)
                return  {
                    colour: colourRed,
                    opacity: opacityDark,
                };
            if (value > value_max*startYellow)
                return  {
                    colour: colourYellow,
                    opacity: opacityLight,
                };
            if (value)   
                return {
                    colour: colourGreen,
                    opacity: opacityWhite,
                };
            return {
                    colour: colourGreen,
                    opacity: 0,
                };
        }

        $('#apply_filter').click(function(){
            sendRequest();
            setMapName();
            setLegend();
            resetRange();
        });

        $('#min_value').change(function() {
            for (let i = 0; i < circles.length; i++) {
                if ( ( min_value.value/100 ) * value_max  >= circles[i].value ) {
                    circles[i].setMap(null);
                } else {
                    circles[i].setMap(map);
                }
            }
        });

    });
    //fisplay sliders value in the form

    min_value_text.innerHTML = '0 ' + measuring_unit; // Display the default slider value

    // Update the current slider value (each time you drag the slider handle)
    min_value.oninput = function() {
        min_value_text.innerHTML = (value_max * this.value/100)+' '+measuring_unit;
    }

    function resetRange() {
        min_value_text.innerHTML = '0 ' + measuring_unit; 
        min_value.value = 0;
    }

}