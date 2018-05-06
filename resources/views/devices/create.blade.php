
<form  action='/addDevice' method="post" >
<div class="modal" id="myModal">
    <div class="modal-dialog">
      <div class="modal-content">
      
        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">Pridėti prietaisą</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
            @csrf
            <div class='form-group'>
                <div class="form-group row">
                    <label for='name'  class="col-md-4 col-form-label text-md-right">{{ __('Pavadinimas') }}</label>
                    <div class='col-md-6'>
                        <input class='form-control' type='text' name='name' placeholder='Pavadinimas'>
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
