@extends('iframe.layouts.index')

@section('title')
    Form Marketing Report
@endsection

@section('body')
<form class="kt-form" action="" method="POST">
    @csrf
  <div class="kt-portlet__body">
    <h4>Pengamatan Pelabuhan/Lapangan</h4>
    <hr>
    <div class="form-group row">
      <label  class="col-2 col-form-label">Tanggal</label>
      <div class="col-10">
       <input class="form-control" type="text" id="kt_datepicker_1" readonly placeholder="Select date" />
      </div>
     </div>
    <div class="form-group row">
      <label  class="col-2 col-form-label">Jam</label>
      <div class="col-10">
       <input class="form-control" type="text" id="kt_timepicker_1" readonly placeholder="Select time"/>
      </div>
     </div>
     <div class="form-group">
      <label>Foto Pelabuhan/Kapal</label>
      <div></div>
        <div class="custom-file">
         <input type="file" class="custom-file-input" multiple/>
         <label class="custom-file-label" for="customFile">Choose file</label>
        </div>
     </div>
     <div class="form-group row">
       <label  class="col-2 col-form-label">Nama Kapal</label>
       <div class="col-10">
        <input class="form-control" type="text" value="" placeholder="Ketik nama kapal"/>
       </div>
      </div>
     <div class="form-group row">
       <label  class="col-2 col-form-label">Nama PT</label>
       <div class="col-10">
        <input class="form-control" type="text" value="" placeholder="Ketik nama PT"/>
       </div>
      </div>
     <div class="form-group row">
       <label  class="col-2 col-form-label">Agent</label>
       <div class="col-10">
        <input class="form-control" type="text" value="" placeholder="Ketik Agent"/>
       </div>
      </div>
     <div class="form-group row">
       <label  class="col-2 col-form-label">CP Agent</label>
       <div class="col-10">
        <input class="form-control" type="text" value="" placeholder="Ketik CP Agent"/>
       </div>
      </div>
      <div class="form-group">
          <label for="exampleTextarea">Nama Nahkoda/Capt</label>
          <input class="form-control" type="text" value="" placeholder="Ketik nama Nahkoda"/>
      </div>
      <div class="form-group">
          <label for="exampleTextarea">Contact Nahkoda/Capt</label>
          <input class="form-control" type="text" value="" placeholder="Ketik contact Nahkoda"/>
      </div>
      <div class="form-group">
          <label for="exampleTextarea">Keterangan lain-lain</label>
          <small>(narasi dari pengamat)</small>
          <textarea name="name" rows="10" cols="90"></textarea>
      </div>
      <div class="form-group row">
        <label  class="col-2 col-form-label">Lanjutkan</label>
        <div class="col-10">
          <select class="form-control">
           <option>Tidak</option>
           <option>Lanjut</option>
          </select>
        </div>
       </div>
       <div class="kt-portlet__foot">
           <div class="kt-form__actions">
               <button type="submit" class="btn btn-primary">Submit</button>
               <button type="reset" class="btn btn-secondary">Cancel</button>
           </div>
       </div>
  </div>
</form>
@endsection\
