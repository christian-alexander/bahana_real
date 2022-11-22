@extends('iframe.layouts.index')

@section('title')
    Detail Laporan Kerusakan
@endsection

@section('body')
<style>
    .black{
        color: black
    }
</style>
<form class="kt-form" method="POST" action="{{route('laporan-perbaikan-kerusakan.approve',[$user->id, $data->id])}}">
@csrf
<div class="kt-portlet__body">
    <div class="form-group">
        <div class="kt-section__info black">No</div>
        <div class="kt-section__content">{{$data->nomor}}</div>
    </div>
    <div class="form-group">
        <div class="kt-section__info black">Ref Laporan Kerusakan No</div>
        <div class="kt-section__content">{{$data->laporanKerusakan->nomor}}</div>
    </div>
    <div class="form-group">
        <div class="kt-section__info black">Nama Kapal</div>
        <div class="kt-section__content">{{$data->laporanKerusakan->nama_kapal}}</div>
    </div>
    <div class="form-group">
        <div class="kt-section__info black">Tanggal</div>
        <div class="kt-section__content">{{Carbon\Carbon::parse($data->tanggal)->format('d-m-Y')}}</div>
    </div>
    <div class="form-group">
        <div class="kt-section__info black">Bagian Kapal</div>
        <div class="kt-section__content">{{$data->bagian_kapal}}</div>
    </div>
    <div class="form-group">
        <div class="kt-section__info black">Note</div>
        <div class="kt-section__content">{{$data->note}}</div>
    </div>
    @if ($data->is_pembuat==1)
        <div class="form-group">
            <div class="kt-section__info black">Di Buat Oleh</div>
            <div class="kt-section__content">{{$data->name_pembuat}}</div>
        </div>
    @elseif($data->is_pembuat==2)
        <div class="form-group">
            <div class="kt-section__info black">Di Tolak Pada ({{$data->name_pembuat}})</div>
            <div class="kt-section__content">{{Carbon\Carbon::parse($data->rejected_pembuat_at)->format('d-m-Y')}}</div>
        </div>
        <div class="form-group">
            <div class="kt-section__info black">Di Tolak Dengan Alasan</div>
            <div class="kt-section__content">{{$data->rejected_pembuat_reason}}</div>
        </div>
    @endif
    @if ($data->is_diperiksa==1)
        <div class="form-group">
            <div class="kt-section__info black">Di Periksa Oleh</div>
            <div class="kt-section__content">{{$data->name_diperiksa}}</div>
        </div>
    @elseif($data->is_diperiksa==2)
        <div class="form-group">
            <div class="kt-section__info black">Di Tolak Pada ({{$data->name_diperiksa}})</div>
            <div class="kt-section__content">{{Carbon\Carbon::parse($data->rejected_diperiksa_at)->format('d-m-Y')}}</div>
        </div>
        <div class="form-group">
            <div class="kt-section__info black">Di Tolak Dengan Alasan</div>
            <div class="kt-section__content">{{$data->rejected_diperiksa_reason}}</div>
        </div>
    @endif
    @if ($data->is_mengetahui_1==1)
        <div class="form-group">
            <div class="kt-section__info black">Di Ketahui Oleh</div>
            <div class="kt-section__content">{{$data->name_mengetahui_1}}</div>
        </div>
    @elseif($data->is_mengetahui_1==2)
        <div class="form-group">
            <div class="kt-section__info black">Di Tolak Pada ({{$data->name_mengetahui_1}})</div>
            <div class="kt-section__content">{{Carbon\Carbon::parse($data->rejected_mengetahui_1_at)->format('d-m-Y')}}</div>
        </div>
        <div class="form-group">
            <div class="kt-section__info black">Di Tolak Dengan Alasan</div>
            <div class="kt-section__content">{{$data->rejected_mengetahui_1_reason}}</div>
        </div>
    @endif
    
    @if ($data->is_mengetahui_2==1)
        <div class="form-group">
            <div class="kt-section__info black">Di Ketahui Oleh</div>
            <div class="kt-section__content">{{$data->name_mengetahui_2}}</div>
        </div>
    @elseif($data->is_mengetahui_2==2)
            <div class="form-group">
                <div class="kt-section__info black">Di Tolak Pada ({{$data->name_mengetahui_2}})</div>
                <div class="kt-section__content">{{Carbon\Carbon::parse($data->rejected_mengetahui_2_at)->format('d-m-Y')}}</div>
            </div>
            <div class="form-group">
                <div class="kt-section__info black">Di Tolak Dengan Alasan</div>
                <div class="kt-section__content">{{$data->rejected_mengetahui_2_reason}}</div>
            </div>
    @endif
    <h4>List Kerusakan</h4>
    <div class="kt-section" style="margin-top: 20px;">
        <div class="kt-section__content wrapper-scroll">
            <table class="table table-bordered target-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama Bagian dan Posisi di Kapal</th>
                        <th>Uraian Kerja Perbaikan</th>
                        <th>Suku Cadang</th>
                        <th>Jumlah Satuan</th>
                        <th>Nomor Bagian Suku Cadang</th>
                        <th>Hasil Perbaikan</th>
                    </tr>
                </thead>
                <tbody>
                    @if (count($data->details)>0)
                        @foreach ($data->details as $detail)
                            <tr>
                                <th>{{ $loop->index+1 }}</th>
                                <td>
                                    {{$detail->nama_bagian_dan_posisi_dikapal}}
                                </td>
                                <td>{{$detail->uraian_pekerjaan}}</td>
                                <td>
                                    {{$detail->suku_cadang}}
                                </td>
                                <td>
                                    {{$detail->jumlah_satuan}}
                                </td>
                                <td>
                                    {{$detail->nomor_suku_cadang}}
                                </td>
                                <td>
                                    {{$detail->hasil_perbaikan}}
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
    @if ($data->can_approve)
        <input type="hidden" name="type_approve" value="{{$data->type_approve}}">
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
        @if ($data->can_approve)
            <a href="javascript:void(0)" data-toggle="modal" data-target="#approve_modal" class="btn btn-primary">Terima</a>
            <a href="javascript:void(0)" data-toggle="modal" data-target="#reject_modal" class="btn btn-secondary">Tolak</a>
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
</form>
<!--end::Modal-->
<form class="kt-form" method="POST" action="{{route('laporan-perbaikan-kerusakan.reject',[$user->id, $data->id])}}">
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
                    <input type="hidden" name="type_approve" value="{{$data->type_approve}}">
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
@endsection