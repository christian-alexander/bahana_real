<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h4 class="modal-title">@lang('app.cabang')</h4>
</div>
<div class="modal-body">
    <div class="portlet-body">
        {!! Form::open(['id'=>'createDepartment','class'=>'ajax-form','method'=>'POST']) !!}
        <div class="form-body">
            <div class="row">
                <div class="col-xs-12">
                    <div class="form-group">
                        <label>@lang('app.name')</label>
                        <input type="text" name="cabang_name" id="cabang_name" class="form-control">
                    </div>
                </div>
            </div>
        </div>
        <div class="form-actions">
            <button type="button" id="save-department" onclick="saveCabang()" class="btn btn-success"> <i class="fa fa-check"></i> @lang('app.save')</button>
        </div>
        {!! Form::close() !!}
    </div>
</div>

<script>

    function saveCabang() {
        var cabangName = $('#cabang_name').val();
        var token = "{{ csrf_token() }}";
        $.easyAjax({
            url: '{{route('admin.cabang.quick-store')}}',
            container: '#createProjectCategory',
            type: "POST",
            data: { 'cabang_name':cabangName, '_token':token},
            success: function (response) {
                if(response.status == 'success'){
                    $('#cabang').html(response.cabangData);
                    $("#cabang").select2();
                    $('#departmentModel').modal('hide');
                }
            }
        })
        return false;
    }
</script>