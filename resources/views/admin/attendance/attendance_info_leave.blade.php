<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4 class="modal-title" ><i class="icon-clock"></i> @lang('app.menu.attendance') @lang('app.details') </h4>
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-md-12">
            <div class="card punch-status">
                <div class="white-box">
                    @foreach ($leave as $val)
                    <div class="punch-info">
                        Tipe: {{$val->display_name}} | {{$val->child->alasan_ijin}}<br>
                        Alasan: {{$val->reason}}
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

</div>