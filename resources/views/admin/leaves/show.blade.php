<div id="event-detail">

    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title"><i class="ti-eye"></i> @lang('app.menu.leaves') @lang('app.details') </h4>
    </div>
    <div class="modal-body">
        {!! Form::open(['id'=>'updateEvent','class'=>'ajax-form','method'=>'GET']) !!}
        <div class="form-body">
            <div class="row">
                <div class="col-md-12 ">
                    <div class="form-group">
                        <label>@lang('modules.leaves.applicantName')</label>
                        <p>
                            {{ ucwords($leave->user->name) }}
                        </p>
                    </div>
                </div>

            </div>

            <div class="row">
                <div class="col-xs-12 ">
                    <div class="form-group">
                        <label>@lang('app.date')</label>
                        <p>{{ $leave->leave_date->format($global->date_format) }} - {{ $leave->leave_date_end->format($global->date_format) }}
                            <label class="label label-{{ $leave->type->color }}">{{ ucwords($leave->type->type_name) }}</label>
                            @if($leave->duration == 'half day')
                             <label class="label label-info">{{ ucwords($leave->duration) }}</label>
                            @endif
                        </p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 ">
                    <div class="form-group">
                        <label>@lang('modules.leaves.reason')</label>
                        <p>{!! $leave->reason !!}</p>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label>@lang('app.status')</label>
                        <p>
                            @if($leave->status == 'approved_atasan_dua')
                                @if ($leave->type_name == 'Dinas Luar Kota')
                                    {{-- check jika dinas luar kota sudah di approve atau tidak --}}
                                    @if ($detail->is_approved_hrd=='1')
                                        <strong class="text-success">Done</strong>
                                    @elseif($detail->approved_by===null)
                                        <strong class="text-warning">In Progress (Menunggu Approval HRD)</strong>
                                    @else
                                        <strong class="text-success">Rejected HRD</strong>
                                    @endif
                                @else
                                    @if ($leave->masking_status=='in progress')
                                        <strong class="text-warning">In Progress</strong>
                                    @elseif($leave->masking_status=='done')
                                        <strong class="text-success">Done</strong>
                                    @elseif($leave->masking_status=='pending')
                                        <strong class="text-warning">Pending </strong>
                                    @else
                                        <strong class="text-danger">@lang('app.rejected')</strong>
                                    @endif
                                @endif
                            @elseif($leave->status == 'pending')
                                <strong class="text-warning">Pending (Menunggu Approval Atasan 1)</strong>
                            @elseif($leave->status == 'approved_atasan_satu')
                                @if ($leave->type_name == 'Dinas sementara')
                                    <strong class="text-success">Done</strong>
                                @else
                                    <strong class="text-warning">In Progress (Menunggu Approval Atasan 2)</strong>
                                @endif
                            @else
                                <strong class="text-danger">@lang('app.rejected')</strong>
                            @endif

                        </p>
                    </div>
                </div>
                {{-- detail here --}}
                @if (!empty($detail))
                    @if ($leave->type_name == 'Ijin')
                        <div class="col-md-12 ">
                            <div class="form-group">
                                <label>Alasan Ijin</label>
                                <p>{{$detail->alasan_ijin}}</p>
                            </div>
                        </div>
                        <div class="col-md-12 ">
                            <div class="form-group">
                                <label>Surat Keterangan Sakit</label><br>
                                <img src="{{asset_url_local_s3($detail->surat_keterangan_sakit)}}" alt="" style="width:150px;">
                            </div>
                        </div>
                    @elseif($leave->type_name == 'Cuti')
                        <div class="col-md-12 ">
                            <div class="form-group">
                                <label>Kategori Cuti</label>
                                <p>{{$detail->kategori_cuti}}</p>
                            </div>
                        </div>
                        <div class="col-md-12 ">
                            <div class="form-group">
                                <label>Potong gaji ?</label>
                                <p>{{$detail->is_potong_gaji==0?'Tidak':'Ya'}}</p>
                            </div>
                        </div>
                    @elseif($leave->type_name == 'Dinas sementara')
                        <div class="col-md-12 ">
                            <div class="form-group">
                                <label>Jam Mulai</label>
                                <p>{{$detail->start_hour}}</p>
                            </div>
                        </div>
                        <div class="col-md-12 ">
                            <div class="form-group">
                                <label>Jam Selesai</label>
                                <p>{{$detail->end_hour}}</p>
                            </div>
                        </div>
                        <div class="col-md-12 ">
                            <div class="form-group">
                                <label>Tujuan</label>
                                <p>{{$detail->destination}}</p>
                            </div>
                        </div>
                    @elseif($leave->type_name == 'Dinas Luar Kota')
                        <div class="col-md-12 ">
                            <div class="form-group">
                                <label>Rute Awal</label>
                                <p>{{$detail->rute_awal}}</p>
                            </div>
                        </div>
                        <div class="col-md-12 ">
                            <div class="form-group">
                                <label>Rute Akhir</label>
                                <p>{{$detail->rute_akhir}}</p>
                            </div>
                        </div>
                        <div class="col-md-12 ">
                            <div class="form-group">
                                <label>Alasan</label>
                                <p>{{$detail->alasan}}</p>
                            </div>
                        </div>
                        <div class="col-md-12 ">
                            <div class="form-group">
                                <label>Biaya</label>
                                <p>{{currency_rupiah($detail->biaya)}}</p>
                            </div>
                        </div>
                    @endif
                @endif
            </div>
        </div>
        {!! Form::close() !!}

    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-white waves-effect" data-dismiss="modal">@lang('app.close')</button>
        {{-- <button type="button" class="btn btn-danger btn-outline delete-event waves-effect waves-light"><i class="fa fa-times"></i> @lang('app.delete')</button> --}}
        {{-- <button type="button" class="btn btn-info save-event waves-effect waves-light"><i class="fa fa-edit"></i> @lang('app.edit') --}}
        </button>
    </div>

</div>

<script>

    $('.save-event').click(function () {
        $.easyAjax({
            url: '{{route('admin.leaves.edit', $leave->id)}}',
            container: '#updateEvent',
            type: "GET",
            data: $('#updateEvent').serialize(),
            success: function (response) {
                if(response.status == 'success'){
                    $('#event-detail').html(response.view);
                }
            }
        })
    })

    $('.delete-event').click(function(){
        swal({
            title: "Are you sure?",
            text: "You will not be able to recover the deleted leave application!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes, delete it!",
            cancelButtonText: "No, cancel please!",
            closeOnConfirm: true,
            closeOnCancel: true
        }, function(isConfirm){
            if (isConfirm) {

                var url = "{{ route('admin.leaves.destroy', $leave->id) }}";

                var token = "{{ csrf_token() }}";

                $.easyAjax({
                    type: 'POST',
                            url: url,
                            data: {'_token': token, '_method': 'DELETE'},
                    success: function (response) {
                        if (response.status == "success") {
                            window.location.reload();
                        }
                    }
                });
            }
        });
    });


</script>