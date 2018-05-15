
<form  action='/createSensor' method="post" >
<div class="modal" id="myModal">
    <div class="modal-dialog">
      <div class="modal-content">
      
        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">Pridėti sensorių</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
            @csrf
            <div class='form-group'>
                <div class="form-group row">
                    <label for='name'  class="col-md-4 col-form-label text-md-right">Pavadinimas</label>
                    <div class='col-md-6'>
                        <input class='form-control' type='text' name='name' placeholder='[teksto eilutė] Pavadinimas' required>
                    </div>
                </div>
                <div class="form-group row">
                    <label for='name'  class="col-md-4 col-form-label text-md-right">Vertės pavadinimas</label>
                    <div class='col-md-6'>
                        <input class='form-control' type='text' name='value_name' placeholder='[teksto eilutė] co; temperatūra;' required>
                    </div>
                </div>                
                <div class="form-group row">
                    <label for='name'  class="col-md-4 col-form-label text-md-right">Matavimo vienetas</label>
                    <div class='col-md-6'>
                        <input class='form-control' type='text' name='measuring_unit' placeholder='[teksto eilutė] mg/m3; C;' required>
                    </div>
                </div>
                <div class="form-group row">
                    <label for='name'  class="col-md-4 col-form-label text-md-right">Tarnavimo laikas, metais</label>
                    <div class='col-md-6'>
                        <input class='form-control' type='text' name='expected_operating_time' placeholder='[realus skaičius] 1.5; 2;' required>
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
                        <input class='form-control' type='text' name='value_max' placeholder='[realus skaičius] 0.45; 0.1;' required>
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
            <button  class='btn btn-info' type='submit' name='button'>Pridėti</button>
        </div>
        
      </div>
    </div>
  </div>

</form>
