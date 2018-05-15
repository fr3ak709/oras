<button class="btn" style='width: 100%' data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">
    Filtrai
</button>
<div class="collapse" id="collapseExample">
    <div class="card card-body">
        <form action='/viewReports' method="post">
            @csrf
            <div class='columns'>
                <div class='column-left'>
                    <label class="col-md-6 col-form-label text-md-right" for="date-group">Datos</label>
                    <div name="date-group">
                        <div class="form-group row date">
                            <label for='date_from' class="col-md-6 col-form-label text-md-right">{{ __('Data nuo') }}</label>
                            <div class='col-md-6'>
                                <input class='form-control' type='date' id='date_from' value={{date( "Y-m-d", strtotime("-7 days") )}} >
                            </div>
                        </div>
                        <div class="form-group row date">
                            <label for='date_to' class="col-md-6 col-form-label text-md-right">{{ __('Data iki') }}</label>
                            <div class='col-md-6'>
                                <input class='form-control' type='date' id='date_to' value={{date("Y-m-d")}} >
                            </div>
                        </div>
                    </div>
                </div>

                <div class='column-right'>
                    <div class="form-group row">
                        <label for='type' class="col-md-6 col-form-label text-md-right">Tipas</label>
                        <div name='type' >
                            <input type="checkbox" name="type" value="generated" checked>Automatiškai generuotos<br>
                            <input type="checkbox" name="type" value="submited"  checked>Įkeltos specialistų<br>
                        </div>
                    </div>
                </div>
            </div>
            <button type='submit' class='btn btn-submit' style='margin : 0 45% 5% 45%' id='apply_filter'>filtruoti </button>
        </form>
    </div>
</div>
<br> <br>