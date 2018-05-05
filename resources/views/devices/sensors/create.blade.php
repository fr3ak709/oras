<form  action={{'/addSensor/'.$devices_id}} method="post">

  <!-- The Modal -->
  <div class="modal" id="myModal">
    <div class="modal-dialog">
      <div class="modal-content">
      
        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">Pridėti sensorių '{{$devices_name}}' prietaisui</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
            @csrf
            <div class='form-group'>
                <div class="form-group row">
                    <label for='title'  class="col-md-4 col-form-label text-md-right">{{ __('Sensorius') }}</label>
                    <div class='col-md-6'>
                    <select class='form-control' name='sensors_id'>
                        @foreach($sensors as $sensor)
                            <option value={{$sensor->id}} >{{ $sensor->name . ' [' .$sensor->value_name.']'  }}</option>
                        @endforeach
                    </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label for='Date' class="col-md-4 col-form-label text-md-right">{{ __('Data') }}</label>
                    <div class='col-md-6'>
                        <input class='form-control' type='date' name='date' value={{date("Y-m-d")}} >
                    </div>
                </div>
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