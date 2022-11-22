<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4 class="modal-title" ><i class="icon-clock"></i> BSSID </h4>
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-md-12">
            <div class="card punch-status">
                <div class="white-box">
                    @if (isset($data))
                        {!! Form::open(['id'=>'updateBSSID','class'=>'ajax-form','method'=>'PUT']) !!}
                            @csrf
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text" class="form-control" id="name" name="name" value="{{$data->name}}" required>
                            </div>
                            <div class="form-group">
                                <label for="bssid">BSSID</label>
                                <input type="text" class="form-control" id="bssid" name="bssid" value="{{$data->bssid}}" required>
                            </div>
                            <button type="button" id="update-form-bssid" class="btn btn-success waves-effect waves-light m-r-10" data-id="{{$data->id}}">
                                @lang('app.save')
                            </button>
                        {!! Form::close() !!}
                    @else
                        {!! Form::open(['id'=>'createBSSID','class'=>'ajax-form','method'=>'POST']) !!}
                            @csrf
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                            <div class="form-group">
                                <label for="bssid">BSSID</label>
                                <input type="text" class="form-control" id="bssid" name="bssid" required>
                            </div>
                            <button type="button" id="save-form-bssid" class="btn btn-success waves-effect waves-light m-r-10">
                                @lang('app.save')
                            </button>
                        {!! Form::close() !!}
                    @endif
                </div>
            </div>
        </div>
    </div>

</div>