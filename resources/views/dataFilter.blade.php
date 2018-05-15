<button class="btn" style='width: 100%' data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">
        Filtrai
    </button>
    <div class="collapse" id="collapseExample">
        <div class="card card-body">
                <form>
                    <div class='columns'>
                        <div class='column-left'>
                            <label class="col-md-6 col-form-label text-md-right" for="date-group">Datos parinktys</label>
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
                            <div class="form-group row radio-buttons">
                                <label class="radio col-md-6 col-form-label text-md-right" for="sensors-radio-group">Sensorių parinktys</label>
                                @foreach($sensors as $sensor )
                                <div class="form-check radio col-md-12"  name="sensors-radio-group">
                                    <label class="radio col-md-6 col-form-label text-md-right" for="sensors">{{$sensor->value_name . ' ' .$sensor->measuring_unit}}</label>
                                    <input class="radio col-md-4 radio-circle" name="sensors" type="radio" value={{$sensor->value_name}} checked>
                                </div> <br />
                                @endforeach
                            
                            </div>
                        </div>
                    </div>
                <button type='button' class='btn btn-submit' style='margin : 0 45% 5% 45%' id='apply_filter'>filtruoti </button>
            </form>
        </div>
    </div>