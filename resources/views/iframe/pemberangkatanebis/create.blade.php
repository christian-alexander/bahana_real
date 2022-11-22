@extends('iframe.layouts.index')

@section('title')
    Form Rencana Pemberangkatan Ebis
@endsection

@section('body')
<form class="kt-form" action="" method="POST">
  @csrf
  <div class="kt-portlet__body">
    <div class="form-group">
      <label>Nama Ebis</label>
      <input type="text" class="form-control" name="" value="" placeholder="Ketik nama ebis">
    </div>
    <div class="form-group">
      <label>Tanggal Berangkat</label>
      <input class="form-control" type="text" id="kt_timepicker_1" readonly placeholder="Select time"/>
    </div>
    <div class="form-group">
      <label>Tujuan</label>
      <input type="text" class="form-control" name="" value="" placeholder="Ketik tujuan">
    </div>
    <div class="form-group">
      <label>Rencana Pemulangan</label>
      <input class="form-control" type="text" id="kt_timepicker_1" readonly placeholder="Select time"/>
    </div>
    <div class="form-group">
      <label>Keterangan</label>
      <textarea class="form-control" name="" rows="8" cols="80" placeholder="Ketik keterangan"></textarea>
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
