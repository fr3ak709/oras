@extends('layouts.app')
@section('Scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js"></script>

@stop
@section('cardHeader')
    Sensoriai
    <button  class='btn btn-info right'  data-toggle="modal" data-target="#myModal"> Pridėti sensorių</button>  
@stop
@section('content')
    <table style='margin: auto;'>
        <tr>
            <td style='width:40%'><strong>Pavadinimas</strong></td>
            <td style='width:10%'><strong>Matuojama vertė</strong></td>
            <td style='width:10%'><strong>Matavimo vienetas</strong></td>
            <td style='width:10%'><strong>maksimali vertė</strong></td>
            <td style='width:10%'><strong>Tinka vartoti, metais</strong></td>
            <td></td>
            <td></td>
        </tr>        
        @foreach ($sensors as $item)
            <td>{{ $item->name }}</td>
            <td>{{ $item->value_name }}</td>
            <td>{{ $item->measuring_unit }}</td>
            <td>{{ $item->value_max }}</td>
            <td>{{ $item->expected_operating_time }}</td>
            <td>
                <button  class='btn btn-info right'  data-toggle="modal" data-target="#sensorsModal{{$item->id}}">Redaguoti</button>
            </td>
            <td> 
                <form action="{{ url('sensor', $item->id ) }}" method="POST">
                    {{ csrf_field() }}
                    {{ method_field('DELETE') }}
                    <button class='btn btn-danger' 
                        onclick='return confirm("Ar tikrai norite pašalinti sensorių {{$item->name}}? Kartu su juo bus ištrinami ir visi duomenys kurious jis surinko. {{$item->ammount_of_data}} Įrašų.")'>Pašalinti</button>
                </form>
            </td>
       
            <form  action='/updateSensor' method="post" >
                <div class="modal" id="sensorsModal{{$item->id}}">
                    <div class="modal-dialog">
                        <div class="modal-content">
                        
                            <!-- Modal Header -->
                            <div class="modal-header">
                            <h4 class="modal-title">Readaguoti {{ $item->name }} sensorių</h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            </div>
                            <input type="hidden" name="id" value={{$item->id}} />
                            <!-- Modal body -->
                            <div class="modal-body">
                                @csrf
                                <div class='form-group'>
                                    <div class="form-group row">
                                        <label for='name'  class="col-md-4 col-form-label text-md-right">Pavadinimas</label>
                                        <div class='col-md-6'>
                                            <input class='form-control' value={{$item->name}} type='text' name='name' placeholder='[teksto eilutė] Pavadinimas'>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for='name'  class="col-md-4 col-form-label text-md-right">Vertės pavadinimas</label>
                                        <div class='col-md-6'>
                                            <input class='form-control' value={{$item->value_name}} type='text' name='value_name' placeholder='[teksto eilutė] co; temperatūra;'>
                                        </div>
                                    </div>                
                                    <div class="form-group row">
                                        <label for='name'  class="col-md-4 col-form-label text-md-right">Matavimo vienetas</label>
                                        <div class='col-md-6'>
                                            <input class='form-control' value={{$item->measuring_unit}} type='text' name='measuring_unit' placeholder='[teksto eilutė] mg/m3; C;'>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for='name'  class="col-md-4 col-form-label text-md-right">Tarnavimo laikas, metais</label>
                                        <div class='col-md-6'>
                                            <input class='form-control' value={{$item->expected_operating_time }} type='text' name='expected_operating_time' placeholder='[realus skaičius] 1.5; 2;'>
                                        </div>
                                    </div>                
                    <!--              <div class="form-group row">
                                        <label for='name'  class="col-md-4 col-form-label text-md-right">Galios suvartojimas</label>
                                        <div class='col-md-6'>
                                            <input class='form-control' type='text' name='power_consumption' placeholder='1 kwh'>
                                        </div>
                                    </div>
                                <div class="form-group row">
                                        <label for='name'  class="col-md-4 col-form-label text-md-right">Paklaida</label>
                                        <div class='col-md-6'>
                                            <input class='form-control' type='text' name='precision' placeholder='0.1 C; 0.5 mg/m3;'>
                                        </div>
                                    </div> -->
                                    <div class="form-group row">
                                        <label for='name'  class="col-md-4 col-form-label text-md-right">Maksimali leidžiama vertė</label>
                                        <div class='col-md-6'>
                                            <input class='form-control' value={{$item->value_max}} type='text' name='value_max' placeholder='[realus skaičius] 0.45; 0.1;'>
                                        </div>
                                    </div>
                    <!--                 <div class="form-group row">
                                        <label for='name'  class="col-md-4 col-form-label text-md-right">operating_temperature_min</label>
                                        <div class='col-md-6'>
                                            <input class='form-control' type='text' name='operating_temperature_min' placeholder='Pavadinimas'>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for='name'  class="col-md-4 col-form-label text-md-right">operating_temperature_max</label>
                                        <div class='col-md-6'>
                                            <input class='form-control' type='text' name='operating_temperature_max' placeholder='Pavadinimas'>
                                        </div>
                                    </div> -->
                                </div>
                            </div>
                            <!-- Modal footer -->
                            <div class="modal-footer">
                                <button  class='btn btn-info' type='submit' name='button'>Išsaugoti</button>
                            </div>
                            
                        </div>
                    </div>
                </div>
            </form>

        </tr>
        @endforeach 
    </table>
    
    @include('sensors/create')
@stop