@extends('layouts.app')
@section('cardHeader')
    Grafikai
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
    </script>
    <script> 
        var myChart = document.getElementById('myChart').getContext('2d');

         sendRequest();
        function sendRequest() {
            var date_from = document.getElementById("date_from").value;
            var date_to   = document.getElementById("date_to").value;
            var sensor    = $('input[name=sensors]:checked').val();
            var city = 'Kaunas';
            var chart ;
            String.prototype.splice = function(idx, rem, str) {
                return this.slice(0, idx) + str + this.slice(idx + Math.abs(rem));
            };
            
            $.ajax({
                //to-do max time between dates 1 day / 1 week / 1 month ?
                url: APP_URL.splice(4, 0, "s")+'/avgdata',
                type: 'GET',
                data: { 
                    date_from: date_from,
                    date_to:   date_to,
                    sensor: sensor,
                },
                success: function(response)
                {
                    updateChart(response);
                }
            });

            function updateChart(response) {
                let dates = [];
                let values = [];
                let sensor = response[0].value_name;
                let measuring_unit = response[0].measuring_unit;
                let max_value = response[0].max_value;

                response.forEach( element => {
                    dates.push(element.date);
                    values.push(Math.round(element.value * 100) / 100);
                })
                console.log(dates, values);
                chart = new Chart(myChart, {
                    type: 'line',
                data: {
                    labels: dates,
                    datasets: [{
                            label: sensor,
                            data: values,
                            fill: false,
                            backgroundColor: "#eebcde ",
                            borderColor: "#eebcde",
                            borderCapStyle: 'butt',
                            borderDash: [5, 5],
                        }]
                },
                    options: {
                        responsive: true,
                        legend: {
                            position: 'bottom',
                        },
                        hover: {
                            mode: 'label'
                        },
                        scales: {
                            xAxes: [{
                                    display: true,
                                    scaleLabel: {
                                        display: true,
                                        labelString: 'Data'
                                    }
                                }],
                            yAxes: [{
                                    display: true,
                                    ticks: {
                                        beginAtZero: false,
                                        steps: 5,
                                        stepValue: 0,
                                        max: max_value
                                    }
                                }]
                        },
                        title: {
                            display: true,
                            text: 'Nuo: '+date_from + ' Iki: ' + date_to + ' \''+ sensor + ' ' + measuring_unit + '\' grafikas'
                        }
                    }
                });
            }
        }
        $('#apply_filter').click(function(){
            sendRequest();
        }); 
    </script>
    

@stop