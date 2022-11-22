@extends('iframe.layouts.index')

@section('title')
    Form SAO Transportir
@endsection

@section('body')
<form class="kt-form" action="" method="POST">
    @csrf
    <div class="kt-portlet__body">
      <div class="form-group row">
        <label  class="col-2 col-form-label">No</label>
        <div class="col-10">
          <input class="form-control" type="text" value="" placeholder="Otomatis"/>
        </div>
      </div>
      <div class="form-group row">
        <label class="col-form-label col-lg-3 col-sm-12 m-right-50">Tanggal</label>
        <div class=" col-lg-4 col-md-9 col-sm-12">
          <input class="form-control" type="text" id="kt_datepicker_1" readonly placeholder="Select date" />
        </div>
        <label class="col-form-label col-lg-3 col-sm-12 m-right-100">Jam</label>
        <div class=" col-lg-4 col-md-9 col-sm-12">
          <input class="form-control" type="text" id="kt_timepicker_1" readonly placeholder="Select time"/>
        </div>
      </div>
      <div class="form-group row">
        <label class="col-2 col-form-label">Wilayah</label>
        <div class="col-10">
          <input class="form-control" type="text" value="" placeholder="Otomatis"/>
        </div>
      </div>
      <div class="form-group row">
        <label class="col-2 col-form-label">No. SAO</label>
        <div class="col-10">
          <input class="form-control" type="text" value="" placeholder="Ketik angka SAO"/>
        </div>
      </div>
      <div class="form-group row">
        <label class="col-2 col-form-label">Customer</label>
        <div class="col-10">
          <input class="form-control" type="text" value="" placeholder="Ketik nama PT"/>
        </div>
      </div>
      <div class="form-group">
          <label>Notes</label>
          <textarea name="" rows="10" cols="90" placeholder="Ketik catatan tambahan jika ada. Contoh : Minta foto bunker,dll..."></textarea>
      </div>
    </div>
    <div class="kt-portlet__foot">
        <div class="kt-form__actions">
            <button type="submit" class="btn btn-primary">Send</button>
            <button type="reset" class="btn btn-secondary">Cancel</button>
        </div>
    </div>
</form>
@endsection
