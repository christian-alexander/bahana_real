<link rel="stylesheet" href="{{ asset('plugins/bower_components/bootstrap-select/bootstrap-select.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/bower_components/custom-select/custom-select.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/bower_components/timepicker/bootstrap-timepicker.min.css') }}">

<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h4 class="modal-title"><i class="fa fa-clock-o"></i> Reject Time Log</h4>
</div>
<div class="modal-body">
    <div class="portlet-body">
        <div class="row">
            <div class="col-md-12">
                {!! Form::open(['id'=>'updateTime','class'=>'ajax-form','method'=>'PUT']) !!}
                <div class="form-body">
                    <div class="row m-t-20">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="memo">Reason</label>
                                <textarea name="reason" id="reason" cols="30" rows="10" class="form-control"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-actions m-t-30">
                    <button type="button" id="update-form" class="btn btn-success"><i class="fa fa-check"></i> Save
                    </button>
                </div>
                {!! Form::close() !!}

            </div>
        </div>

    </div>
</div>

<script src="{{ asset('plugins/bower_components/bootstrap-select/bootstrap-select.min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/custom-select/custom-select.min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/timepicker/bootstrap-timepicker.min.js') }}"></script>


<script>
    $('#update-form').click(function () {
        $.easyAjax({
            url: '{{route('admin.time-logs.reject', $timeLog->id)}}',
            container: '#updateTime',
            type: "POST",
            data: $('#updateTime').serialize(),
            success: function (response) {
                console.log(response);
                $('#editTimeLogModal').modal('hide');
                window.LaravelDataTables["all-time-logs-table"].draw();
                // table._fnDraw();
            }
        })
    });
</script>