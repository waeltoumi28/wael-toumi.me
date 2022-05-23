
    <!-- Modal Currency -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Currencies</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      {!! Form::open(['action' => 'DefaultCurrencyController@store', 'method' => 'POST']) !!}
      <div class="modal-body">
        <div class="form-group">
            <label for="exampleFormControlSelect1">Pick a Default Currency</label>
            <select class="form-control" id="exampleFormControlSelect1" name="currency">
            @if(count($currencies) > 0)
              @foreach($currencies as $money)
                <option value="{{$money->id}}">{{$money->iso_code}} - {{$money->name}}</option>
              @endforeach
            @endif
            </select>
        </div> 
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Save changes</button>
      </div>
      {!! Form::close() !!}
    </div>
  </div>
</div>