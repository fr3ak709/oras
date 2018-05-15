var myChart = document.getElementById('myChart').getContext('2d');
var value_max = 0;
var value_name = '';
var measuring_unit = '';
var chart = new Chart(myChart, { type: 'line'});
sendRequest();
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
    setChartName();
    String.prototype.splice = function(idx, rem, str) {
        return this.slice(0, idx) + str + this.slice(idx + Math.abs(rem));
    };
    
    $.ajax({
        //to-do max time between dates 1 day / 1 week / 1 month ?
        url: APP_URL.splice(4, 0, "")+'/avgdata',
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
    function setChartName () {
        document.getElementById("graphName").innerHTML = 'Grafikas \''+value_name + '\' nuo: '+date_from + ' iki: '+date_to;
    }
    function updateChart(response) {
        let dates = [];
        let values = [];

        response.forEach( element => {
            dates.push(element.date);
            values.push(Math.round(element.value * 100) / 100);
        })

        chart = new Chart(myChart, {
            type: 'line',
            data: {
                labels: dates,
                datasets: [{
                        label: value_name,
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
                                max: value_max
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