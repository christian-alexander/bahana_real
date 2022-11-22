@extends('iframe.layouts.index')

@section('title')
    Detail SPK
@endsection

@section('after-title')
    <button class="btn btn-sm btn-primary pull-right" style="margin-top: 14px;" data-toggle="modal" data-target="#modal-history">History</button>
@endsection

@section('body')
<style>
    .black{
        color: black
    }
</style>
<form class="kt-form" method="POST" action="{{route('spk.approve',[$user->id, $data->id])}}">
@csrf
<div class="kt-portlet__body">
    @if ($data->can_set_no_pp==1)
        {{-- <div class="form-group">
            <label>No PP</label>
            <input type="text" class="form-control" name="no_pp">
            @if ($errors->has('no_pp'))
                <div class="invalid-feedback" style="display: block">{{$errors->first('no_pp')}}</div>
            @endif
        </div> --}}
        <div class="form-group">
            <label>Cabang</label>
            <select class="form-control kt-select2" name="cabang">
                @foreach ($cabang as $item)
                    <option value="{{$item->lok}}">{{$item->nm}}</option>
                @endforeach
            </select>
            @if ($errors->has('cabang'))
                <div class="invalid-feedback" style="display: block">{{$errors->first('cabang')}}</div>
            @endif
        </div>
    @endif
    @if (!empty($json_cabang))
        {{-- <div class="form-group">
            <div class="kt-section__info black">No PP</div>
            <div class="kt-section__content">{{$data->pp_id}}</div>
        </div> --}}
        <div class="form-group">
            <div class="kt-section__info black">Cabang</div>
            <div class="kt-section__content">{{$json_cabang['nm']}}</div>
        </div>
    @endif
    <div class="form-group">
        <div class="kt-section__info black">MT / SPOB</div>
        <div class="kt-section__content">{{$data->mt_or_spob}}</div>
    </div>
    <div class="form-group">
        <div class="kt-section__info black">No</div>
        <div class="kt-section__content">{{$data->no}}</div>
    </div>
    <div class="form-group">
        <div class="kt-section__info black">Keperluan</div>
        <div class="kt-section__content">{{$data->keperluan}}</div>
    </div>
    <div class="form-group">
        <div class="kt-section__info black">Dibuat Tanggal</div>
        <div class="kt-section__content">{{Carbon\Carbon::parse($data->tanggal)->format('d-m-Y')}}</div>
    </div>
    <div class="form-group">
        <div class="kt-section__info black">Dibuat Oleh</div>
        <div class="kt-section__content">{{$data->user_name}}</div>
    </div>
    <div class="form-group">
        <div class="kt-section__info black">Status</div>
        <div class="kt-section__content">{{$data->status}}</div>
    </div>
    <div class="form-group">
        <div class="kt-section__info black">Pengajuan Terakhir</div>
        <div class="kt-section__content">{{empty($data->status_approval)?'-':$data->status_approval}}</div>
    </div>
    <h4>List Barang</h4>
    <div class="kt-section" style="margin-top: 20px;">
        <div class="kt-section__content wrapper-scroll">
            <table class="table table-bordered target-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Barang</th>
                        <th>Nama barang di input</th>
                        <th>Jumlah barang diminta</th>
                        <th>Jumlah barang disetujui</th>
                        <th>Keterangan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @if (count($data->details)>0)
                        @foreach ($data->details as $detail)
                            <tr>
                                <th>{{ $loop->index+1 }}</th>
                                <td>
                                    @if (empty($detail->barang_id))
                                        @if ($data->can_change_barang_id==1)
                                            <select class="form-control kt-select2" id="kt_select2_1" name="barang_id[]">
                                                @foreach ($barang as $item)
                                                    <option value="{{$item->kdstk}}">{{$item->nm}}</option>
                                                @endforeach
                                            </select>
                                            
                                            <input type="hidden" name="detail_id[]" class="form-control" value="{{$detail->id}}">
                                        @endif
                                    @else
                                        {{$detail->barang_etc}}
                                    @endif
                                </td>   
                                <td>{{empty($detail->barang_etc)?'-':$detail->barang_etc}}</td>
                                <td>{{$detail->barang_diminta}}</td>
                                <td>
                                    @if ($data->can_change_qty==1)
                                        ( {{$detail->barang_disetujui}} )
                                        <input type="text" name="qty_disetujui[]" class="form-control">
                                        <input type="hidden" name="detail_id[]" class="form-control" value="{{$detail->id}}">
                                    @else
                                        {{$detail->barang_disetujui}}
                                    @endif
                                </td>
                                <td>{{$detail->ket}}</td>
                                <td>
                                    @if ($data->can_see_history==1)
                                        <button type="button" class="btn btn-sm btn-primary check-history" style="margin-bottom: 10px;" data-barang="{{$detail->barang_id}}">History</button>
                                    @endif
                                    @if ($data->can_delete_barang==1)
                                        <button type="button" class="btn btn-sm btn-warning" data-toggle="modal" data-target="#kt_modal_{{$detail->id}}">Hapus</button>
                                        <!--begin::Modal Delete-->
                                        <div class="modal fade" id="kt_modal_{{$detail->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">Peringatan</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p>Data akan dihapus. Anda yakin?</p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tidak</button>
                                                        <a href="{{route('spk.delete',[$user->id,$data->id,$detail->id])}}" class="btn btn-primary">Ya</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!--end::Modal-->
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr class="no-data">
                            <td colspan="7"><center>Tidak ada data</center></td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
    @if ($data->can_report_performance==1)
        <h4>Report Performance</h4>
        <div class="kt-section" style="margin-top: 20px;">
            <div class="kt-section__content">
                <div class="form-group">
                    <label>Point</label>
                    <input type="text" class="form-control" name="point" >
                </div>
                <div class="form-group form-group-last">
                    <label for="exampleTextarea">Alasan</label>
                    <textarea class="form-control" id="exampleTextarea" rows="3" name="alasan"></textarea>
                </div>
            </div>
        </div>
    @endif
    @if ($data->require_signature == 1)
        <label>Tanda tangan</label>
        <div id="signature-pad" class="signature-pad" style="height: 250px;border: none;box-shadow: none;padding: 0">
            <div class="signature-pad--body">
                <canvas style="width: 100%;height: 100%;border: solid 1px #ccc;"></canvas>
            </div>
            <textarea id="signature64" name="tanda_tangan" style="display: none"></textarea>
            <button type="button" id="clear" class="btn btn-primary btn-sm form-control" data-action="clear">Bersihkan</button>
            @if ($errors->has('tanda_tangan'))
                <div class="invalid-feedback" style="display: block">{{$errors->first('tanda_tangan')}}</div>
            @endif
        </div>
    @endif
</div>
<div class="kt-portlet__foot">
    <div class="kt-form__actions">
        @if ($data->can_approve==1)
            <a href="javascript:void(0)" data-toggle="modal" data-target="#approve_modal" class="btn btn-primary">Terima</a>
            <a href="javascript:void(0)" data-toggle="modal" data-target="#reject_modal" class="btn btn-secondary">Tolak</a>
        @endif
        @if ($data->can_verif==1)
            <a href="javascript:void(0)" data-toggle="modal" data-target="#verif_modal" class="btn btn-primary">Verif</a>
        @endif
    </div>
</div>

<!--begin::Modal history-->
<div class="modal fade" id="modal-history" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">History</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body wrapper-scroll">
                <table class="table table-bordered target-table output-search">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Aktivitas</th>
                            <th>Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (count($activity)>0)
                            @foreach ($activity as $item)
                                <tr>
                                    <th>{{$loop->index+1}}</th>
                                    <td>{{$item->activity}}</td>
                                    <td>{{Carbon\Carbon::parse($item->created_at)->format('d-m-Y H:i:s')}}</td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="5" style="text-align: center">Tidak ada data</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
<!--end::Modal-->
<!--begin::Modal approve-->
<div class="modal fade" id="approve_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Peringatan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <p>Apakah anda yakin untuk menyetujui permintaan ini?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tidak</button>
                <button type="submit" class="btn btn-primary" id="submitBtn">Ya</button>
            </div>
        </div>
    </div>
</div>
<!--end::Modal-->
<!--begin::Modal approve-->
<div class="modal fade" id="verif_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Peringatan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <p>Apakah anda yakin untuk memverifikasi data ini?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tidak</button>
                <button type="submit" class="btn btn-primary">Ya</button>
            </div>
        </div>
    </div>
</div>
<!--end::Modal-->
</form>

<form class="kt-form" method="GET" action="{{route('spk.reject',[$user->id, $data->id])}}">
@csrf
<!--begin::Modal reject-->
<div class="modal fade" id="reject_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Peringatan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <p>Apakah anda yakin untuk menolak permintaan ini?</p>
                <div class="form-group form-group-last">
                    <label for="exampleTextarea">Alasan</label>
                    <textarea class="form-control" rows="3" name="alasan_reject"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tidak</button>
                <button type="submit" class="btn btn-primary">Ya</button>
            </div>
        </div>
    </div>
</div>
<!--end::Modal-->
</form>
<!--begin::Modal approve-->
<div class="modal fade" id="history_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">History</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group row">
                    <label for="example-date-input" class="col-2 col-form-label">Dari</label>
                    <div class="col-10">
                        <input class="form-control" type="date" id="start_date" name="start_date" value="{{Carbon\Carbon::now()->format('Y-m-d')}}" id="example-date-input">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="example-date-input" class="col-2 col-form-label">Sampai</label>
                    <div class="col-10">
                        <input class="form-control" type="date" id="end_date" name="end_date" value="{{Carbon\Carbon::now()->format('Y-m-d')}}" id="example-date-input">
                    </div>
                </div>
                <button type="button" class="btn btn-sm btn-primary pull-right cari-barang" data-barang="0" style="margin-bottom: 20px;">Cari</button>
                <div class="wrapper-scroll">
                    <table class="table table-bordered target-table output-search">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Barang id</th>
                                <th>Jumlah barang diminta</th>
                                <th>Jumlah barang disetujui</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="5" style="text-align: center">Tidak ada data</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
<!--end::Modal-->
@endsection
@section('script')
    <script>
        $(document).ready(function(){
            // var signaturePad = $('#signaturePad').signature({syncField: '#signature64', syncFormat: 'PNG'});
            // $('#clear').click(function(e) {
            //     e.preventDefault();
            //     signaturePad.signature('clear');
            //     $("#signature64").val('');
            // });
            $('.check-history').on('click', function(){
                var barang_id = $(this).data('barang');
                $('#history_modal').find('.cari-barang').attr('data-barang', barang_id);
                var html = "<tr><td colspan='5' style='text-align: center'>Tidak ada data</td></tr>" 
                $(".output-search tbody").empty();
                $(".output-search tbody").append(html);
                $('#history_modal').modal('toggle');
            })
            $('.cari-barang').on('click', function(){
                var barang_id = $(this).data('barang');
                var start_date = $('#start_date').val();
                var end_date = $('#end_date').val();
                var url = "{{route('spk.history',[':id',':start_date',':end_date'])}}"
                url = url.replace(':id', barang_id);
                url = url.replace(':start_date', start_date);
                url = url.replace(':end_date', end_date);
                $.ajax({
                    url: url,
                    type: "get",
                    success: function (resp) {
                        // console.log(resp)
                        $(".output-search tbody").empty();
                        $(".output-search tbody").append(resp);
                        // You will get response from your PHP page (what you echo or print)
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.log(textStatus, errorThrown);
                    }
                });
            })
        })
    </script>
@endsection