@extends('iframe.layouts.index')

@section('title')
    Detail SPK
@endsection

@section('body')
<style>
    .black{
        color: black
    }
</style>
<div class="kt-portlet__body">
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
        <div class="kt-section__info black">Tanggal</div>
        <div class="kt-section__content">{{$data->tanggal}}</div>
    </div>
    <div class="form-group">
        <div class="kt-section__info black">Dibuat Oleh</div>
        <div class="kt-section__content">{{$data->user_name}}</div>
    </div>
    <h4>List Barang</h4>
    <div class="kt-section" style="margin-top: 20px;">
        <div class="kt-section__content">
            <table class="table table-bordered target-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Barang id</th>
                        <th>Barang etc</th>
                        <th>Jumlah barang diminta</th>
                        <th>Jumlah barang disetujui</th>
                        <th>Keterangan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @if (count($data->details)>0)
                        @foreach ($data->details as $barang)
                            <tr>
                                <th>{{ $loop->index+1 }}</th>
                                <td>{{$barang->barang_id}}</td>
                                <td>{{empty($barang->barang_etc)?'-':$barang->barang_etc}}</td>
                                <td>{{$barang->barang_diminta}}</td>
                                <td>{{$barang->barang_disetujui}}</td>
                                <td>{{$barang->ket}}</td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-info">Hapus</button>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr class="no-data">
                            <td colspan="5"><center>Tidak ada data</center></td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="kt-portlet__foot">
    <div class="kt-form__actions">
        <a href="http://google.com/" target='_parent' class="btn btn-primary">Terima</a>
        <a href="#" class="btn btn-secondary">Tolak</a>
    </div>
</div>
<!--begin::Modal-->
<div class="modal fade" id="kt_modal_1" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-index="0">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Peringatan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <p>Data akan dihapus.Anda yakin?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tidak</button>
                <button type="button" class="btn btn-primary delete-confirm">Ya</button>
            </div>
        </div>
    </div>
</div>

<!--end::Modal-->
@endsection
@section('script')
    <script>
        $(document).ready(function(){
            
        })
    </script>
@endsection