@extends('iframe.layouts.index')

@section('title')
    Detail Form Permintaan Dana
@endsection

@section('body')
<style>
    .black{
        color: black
    }
</style>
<form class="kt-form" method="POST" action="{{route('formpermintaan-dana.approve',[$user->id, $data->id])}}">
@csrf
<div class="kt-portlet__body">
    <div class="form-group">
        <div class="kt-section__info black">No Permintaan Dana</div>
        <div class="kt-section__content">{{$data->no}}</div>
    </div>
    <div class="form-group">
        <div class="kt-section__info black">Nama PT</div>
        <div class="kt-section__content">{{$data->subcompany}}</div>
    </div>
    <div class="form-group">
        <div class="kt-section__info black">Nama</div>
        <div class="kt-section__content">{{$data->nama}}</div>
    </div>
    <div class="form-group">
        <div class="kt-section__info black">Bagian</div>
        <div class="kt-section__content">{{$data->department}}</div>
    </div>
    <div class="form-group">
        <div class="kt-section__info black">Tanggal</div>
        <div class="kt-section__content">{{Carbon\Carbon::parse($data->tanggal)->format('d-m-Y')}}</div>
    </div>
    <div class="form-group">
        <div class="kt-section__info black">Keperluan</div>
        <div class="kt-section__content">{{$data->keperluan}}</div>
    </div>
    <div class="form-group">
        <div class="kt-section__info black">Nominal</div>
        <div class="kt-section__content">{{$data->nominal}}</div>
    </div>
    <div class="form-group">
        <div class="kt-section__info black">Terbilang</div>
        <div class="kt-section__content">{{$data->terbilang}}</div>
    </div>
    <div class="form-group">
        <div class="kt-section__info black">Unsur PPH</div>
        <div class="kt-section__content">{{$data->unsur_pph}}</div>
    </div>
    <div class="form-group">
        <div class="kt-section__info black">Nominal PPH</div>
        <div class="kt-section__content">{{$data->nominal_pph}}</div>
    </div>
    <div class="form-group">
        <div class="kt-section__info black">Approval Pajak</div>
        <div class="kt-section__content">{{$data->approval_pajak}}</div>
    </div>
    <div class="form-group">
        <div class="kt-section__info black">Diperiksa</div>
        <div class="kt-section__content">{{$data->diperiksa_1}}</div>
    </div>
    <div class="form-group">
        <div class="kt-section__info black">Mengetahui</div>
        <div class="kt-section__content">{{$data->mengetahui}}</div>
    </div>
    <div class="form-group">
        <div class="kt-section__info black">Disetujui</div>
        <div class="kt-section__content">{{$data->disetujui_1}}</div>
    </div>
    <div class="form-group">
        <div class="kt-section__info black">Note</div>
        <div class="kt-section__content">{{$data->note}}</div>
    </div>
    <div class="form-group">
        <div class="kt-section__info black">Attachment</div>
        <div class="kt-section__content">{{$data->image}}</div>
    </div>
    {{-- <div class="form-group">
        <div class="kt-section__info black">Dibuat Oleh</div>
        <div class="kt-section__content">{{$data->name_pembuat}}</div>
    </div> --}}
    <div class="form-group">
        <div class="kt-section__info black">Status</div>
        <div class="kt-section__content">{{$data->status}}</div>
    </div>
    <div class="form-group">
        <div class="kt-section__info black">Pengajuan Terakhir</div>
        <div class="kt-section__content">{{empty($data->status_approval)?'-':$data->status_approval}}</div>
    </div>
    
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
    <table class="table">
        <thead>
            <tr>
                <th>No</th>
                <th>Activity</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($activity as $val)
                <tr>
                    <td>{{$loop->iteration}}</td>
                    <td>{{$val}}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
<div class="kt-portlet__foot">
    <div class="kt-form__actions">
        @if ($data->can_approve==1)
            <input type="hidden" name="type_approve" value="{{$data->type_approve}}">
            <a href="javascript:void(0)" data-toggle="modal" data-target="#approve_modal" class="btn btn-primary">Terima</a>
            <a href="javascript:void(0)" data-toggle="modal" data-target="#reject_modal" class="btn btn-secondary">Tolak</a>
            @else
            <a href="/formpermintaan-dana/detail/{{$user_id}}/{{ $data->id }}/cetak_pdf" class="btn btn-primary" target="_blank">CETAK PDF</a>
        @endif
        @if ($data->can_verif==1)
            <a href="javascript:void(0)" data-toggle="modal" data-target="#verif_modal" class="btn btn-primary">Verif</a>
        @endif
    </div>
</div>

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

<form class="kt-form" method="POST" action="{{route('formpermintaan-dana.reject',[$user->id, $data->id])}}">
@csrf
<input type="hidden" name="type_approve" value="{{$data->type_approve}}">
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
            //code
        })
    </script>
@endsection