@extends('iframe.layouts.index')

@section('title')
    Form Status Aset Owner
@endsection

@section('body')

<form class="kt-form" action="/form-status-aset-owner/doInput" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="kt-portlet__body">

        <div class="form-group">
            <label>Wilayah Aset</label>
            <input type="text" class="form-control" name="wilayah_aset">
            @if ($errors->has('wilayah_aset'))
            <div class="invalid-feedback" style="display: block">{{$errors->first('wilayah_aset')}}</div>
            @endif
        </div>

        <div class="form-group">
            <label>No Sertifikat</label>
            <input type="text" class="form-control" name="no_sertifikat">
            @if ($errors->has('no_sertifikat'))
            <div class="invalid-feedback" style="display: block">{{$errors->first('no_sertifikat')}}</div>
            @endif
        </div>

        <div class="form-group">
            <label>Nama Aset</label>
            <input type="text" class="form-control" name="nama_aset">
            @if ($errors->has('nama_aset'))
            <div class="invalid-feedback" style="display: block">{{$errors->first('nama_aset')}}</div>
            @endif
        </div>

        <div class="form-group">
            <label>NJOP</label>
            <input type="text" class="form-control" name="njop">
            @if ($errors->has('njop'))
            <div class="invalid-feedback" style="display: block">{{$errors->first('njop')}}</div>
            @endif
        </div>

        <div class="form-group">
            <label>Luas</label>
            <input type="number" class="form-control" name="luas">
            @if ($errors->has('luas'))
            <div class="invalid-feedback" style="display: block">{{$errors->first('luas')}}</div>
            @endif
        </div>

        <div class="form-group">
            <label>Nama Kepemilikan</label>
            <input type="text" class="form-control" name="nama_kepemilikan">
            @if ($errors->has('nama_kepemilikan'))
            <div class="invalid-feedback" style="display: block">{{$errors->first('nama_kepemilikan')}}</div>
            @endif
        </div>

        <div class="form-group">
            <label>Posisi Dokumen</label>
            <input type="text" class="form-control" name="posisi_dokumen">
            @if ($errors->has('posisi_dokumen'))
            <div class="invalid-feedback" style="display: block">{{$errors->first('posisi_dokumen')}}</div>
            @endif
        </div>

        <div class="form-group">
            <label>Tanggal Perolehan</label>
            <input id="tanggal_perolehan" name="tanggal_perolehan" placeholder="YYYY/MM/DD" type="date" class="form-control kt-select2">
            @if ($errors->has('tanggal_perolehan'))
            <div class="invalid-feedback" style="display: block">{{$errors->first('tanggal_perolehan')}}</div>
            @endif
        </div>

        <div class="form-group">
            <label>Tanggal Masuk Brankas</label>
            <input id="tanggal_masuk_brankas" name="tanggal_masuk_brankas" placeholder="YYYY/MM/DD" type="date" class="form-control kt-select2">
            @if ($errors->has('tanggal_masuk_brankas'))
            <div class="invalid-feedback" style="display: block">{{$errors->first('tanggal_masuk_brankas')}}</div>
            @endif
        </div>

        <div class="form-group row">
            <label for="note" class="col-4 col-form-label">Note</label>
            <textarea id="note" name="note" cols="40" rows="5" class="form-control"></textarea>
            @if ($errors->has('note'))
            <div class="invalid-feedback" style="display: block">{{$errors->first('note')}}</div>
            @endif
        </div>

        <div class="form-group">
            <label>Status</label>
            <input type="text" class="form-control" name="status">
            @if ($errors->has('status'))
            <div class="invalid-feedback" style="display: block">{{$errors->first('status')}}</div>
            @endif
        </div>

        <div class="form-group">
            <label>Jenis Aset</label>
            <input type="text" class="form-control" name="jenis_aset">
            @if ($errors->has('jenis_aset'))
            <div class="invalid-feedback" style="display: block">{{$errors->first('jenis_aset')}}</div>
            @endif
        </div>

      






    </div>
    <div class="kt-portlet__foot">
        <div class="kt-form__actions">
            <button type="submit" class="btn btn-primary">Submit</button>
            <button type="reset" class="btn btn-secondary">Cancel</button>
        </div>
    </div>
</form>
@endsection

@section('script')
<script type="text/javascript">
$(document).ready(function() {
    $('#tabledata').DataTable( {
        "scrollX": true ,
        "scrollY":"200px",
        "scrollCollapse": true,
        "paging": false
    } );
  } );

$('#kt_select2_1_tagihan, #kt_select2_1_pilihan').select2({
    placeholder: "Select a state"
});
</script>
@endsection
