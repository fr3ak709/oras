<div class="modal" id="myModal">
    <div class="modal-dialog">
      <div class="modal-content">
      
        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">Pridėti ataskaitą</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        
        <!-- Modal body -->
        <form  action='/uploadReport' method="post" enctype="multipart/form-data">
            <div class="modal-body">
                @csrf
                <div class='form-group'>
                    <div class="form-group row">
                        <label for='title'  class="col-md-4 col-form-label text-md-right">{{ __('Pavadinimas') }}</label>
                        <div class='col-md-6'>
                            <input class='form-control' type='text' name='title' placeholder='Pavadinimas'>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for='Date' class="col-md-4 col-form-label text-md-right">{{ __('Data') }}</label>
                        <div class='col-md-6'>
                            <input class='form-control' type='date' name='date' value={{date("Y-m-d")}} >
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for='Date' class="col-md-4 col-form-label text-md-right">{{ __('Failas') }}</label>
                        <div class='col-md-6 '>
                            <input class='form-control-file' type="file" accept='application/pdf' name='report' value=''>
                        </div>
                    </div>
                    <div class="form-group row mb-0">
                        
                    </div>
                </div>
            </div>
        
            <!-- Modal footer -->
            <div class="modal-footer">
                <button  class='btn btn-info' type='submit' name='button'>Pridėti</button>
            </div>
        </form>
        
      </div>
    </div>
</div>