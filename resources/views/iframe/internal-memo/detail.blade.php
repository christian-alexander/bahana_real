@extends('iframe.layouts.index')

@section('title')
    Detail Internal Memo
@endsection

@section('body')
<style>
    .black{
        color: black
    }
</style>
<form class="kt-form" method="POST" action="{{route('internal-memo.approve',[$user->id, $data->id])}}">
@csrf
<div class="kt-portlet__body">
    <div class="form-group">
        <div class="kt-section__info black">Anak Perusahaan</div>
        <div class="kt-section__content">{{$data->subcompany}}</div>
    </div>
    <div class="form-group">
        <div class="kt-section__info black">Department</div>
        <div class="kt-section__content">{{$data->department}}</div>
    </div>
    <div class="form-group">
        <div class="kt-section__info black">Wilayah</div>
        <div class="kt-section__content">{{$data->wilayah}}</div>
    </div>
    <div class="form-group">
        <div class="kt-section__info black">Dari</div>
        <div class="kt-section__content">{{$data->name_from}}</div>
    </div>
    <div class="form-group">
        <div class="kt-section__info black">Kepada</div>
        <div class="kt-section__content">{{$data->name_to}}</div>
    </div>
    <div class="form-group">
        <div class="kt-section__info black">Dibuat Tanggal</div>
        <div class="kt-section__content">{{Carbon\Carbon::parse($data->tanggal)->format('d-m-Y')}}</div>
    </div>
    <div class="form-group">
        <div class="kt-section__info black">Tempat</div>
        <div class="kt-section__content">{{$data->tempat}}</div>
    </div>
    <div class="form-group">
        <div class="kt-section__info black">Perihal</div>
        <div class="kt-section__content">{{$data->perihal}}</div>
    </div>
    <div class="form-group">
        <div class="kt-section__info black">Sifat</div>
        <div class="kt-section__content">{{$data->sifat}}</div>
    </div>
    <div class="form-group">
        <div class="kt-section__info black">Berita</div>
        <div class="kt-section__content">{{$data->berita}}</div>
    </div>
    <div class="form-group">
        <div class="kt-section__info black">Dibuat Oleh</div>
        <div class="kt-section__content">{{$data->name_pembuat}}</div>
    </div>
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
</div>
<div class="kt-portlet__foot">
    <div class="kt-form__actions">
        @if ($data->can_approve==1)
            <input type="hidden" name="type_approve" value="{{$data->type_approve}}">
            <a href="javascript:void(0)" data-toggle="modal" data-target="#approve_modal" class="btn btn-primary">Terima</a>
            <a href="javascript:void(0)" data-toggle="modal" data-target="#reject_modal" class="btn btn-secondary">Tolak</a>
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

<form class="kt-form" method="POST" action="{{route('internal-memo.reject',[$user->id, $data->id])}}">
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