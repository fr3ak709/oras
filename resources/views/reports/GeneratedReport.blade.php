<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <style type="text/css" >
            table {
                margin: auto; 
                width:95%; 
                border: solid 1px;
                font-family: arial, sans-serif;
                border-collapse: collapse;
                width: 100%;
            }
            tr:first-child {background: #FF0}
            tr:nth-child(2n+3) {background: #CCC}
            .center {text-align: center}
        </style>
    </head>
    <body>
        <h2 class='center'> ' {{$date}} ' dienos ataskaita</h2>
        @foreach($sensors as $sensor) 
            <h3 class='center'>' {{$sensor->value_name}} ' vidurkis Kaune</h3>
            <table>
                <tr>
                    <td> Valanda </td>
                    <td> Vertė {{$sensor->measuring_unit}} </td>
                </tr>   
                @foreach($sensor->data as $item)      
                    <tr>
                        <td>{{$item->hour}}</td>
                        <td>{{number_format((float)$item->value, 2, '.', '')}}</td>
                    </tr>
                @endforeach
            </table>
            <br>

            @if(!$sensors->data_above_allowed->isEmpty()) 
            <table>
            <tr>
                <td> Laikas </td>
                <td> Platuma </td>
                <td> Ilguma </td>
                <td> Vertė {{$sensor->measuring_unit}} </td>
            </tr> 
           
                @foreach($sensors->data_above_allowed as $item) 
                    <tr>
                        <td>{{$item->date}}</td>
                        <td>{{$item->long}}</td>
                        <td>{{$item->lat}}</td>
                        <td>{{number_format((float)$item->value, 2, '.', '')}}</td>
                    </tr>
                @endforeach
            </table>            
            @endif
        @endforeach
    </body>
    
</html>

