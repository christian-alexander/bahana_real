@extends('iframe.layouts.index')

@section('title')
    Form Follow Up Customer
@endsection

@section('body')
<form class="kt-form" action="" method="POST">
  @csrf
  <div class="kt-portlet__body">
    <h4>Telepon Customer</h4>
    <hr>
    <div class="form-group row">
      <label  class="col-2 col-form-label">Tanggal</label>
      <div class="col-10">
       <input class="form-control" type="text" id="kt_datepicker_1" readonly placeholder="Select date" />
      </div>
     </div>
    <div class="form-group row">
      <label  class="col-2 col-form-label">Jam Mulai</label>
      <div class="col-10">
       <input class="form-control" type="text" id="kt_timepicker_1" readonly placeholder="Select time"/>
      </div>
     </div>
    <div class="form-group row">
      <label  class="col-2 col-form-label">Jam Selesai</label>
      <div class="col-10">
       <input class="form-control" type="text" id="kt_timepicker_1" readonly placeholder="Select time"/>
      </div>
     </div>
     <hr>
    <h4>Kunjungan Customer</h4>
    <hr>
    <div class="form-group row">
      <label  class="col-2 col-form-label">Tanggal</label>
      <div class="col-10">
       <input class="form-control" type="text" id="kt_datepicker_1_validate" readonly placeholder="Select date" />
      </div>
     </div>
    <div class="form-group row">
      <label  class="col-2 col-form-label">Jam Mulai</label>
      <div class="col-10">
       <input class="form-control" type="text" id="kt_timepicker_1" readonly placeholder="Select time"/>
      </div>
     </div>
    <div class="form-group row">
      <label  class="col-2 col-form-label">Jam Selesai</label>
      <div class="col-10">
       <input class="form-control" type="text" id="kt_timepicker_1" readonly placeholder="Select time"/>
      </div>
     </div>
     <div class="form-group">
      <label>Foto Kantor Customer</label>
      <div></div>
        <div class="custom-file">
         <input type="file" class="custom-file-input" id="customFile" multiple/>
         <label class="custom-file-label" for="customFile">Attach foto</label>
        </div>
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
