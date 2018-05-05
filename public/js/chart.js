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