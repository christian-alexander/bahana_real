@extends('iframe.layouts.index')

@section('title')
    Form Kegiatan Audit
@endsection

@section('body')
<form class="kt-form" action="" method="POST">
  @csrf
  <div class="kt-portlet__body">
    <div class="form-group row">
      <label  class="col-2 col-form-label">Auditor</label>
      <div class="col-10">
        <select class="form-control kt-select2" id="kt_select2_1_auditor" name="param">
          <option value="EB01">EB01</option>
          <option value="EB02">EB02</option>
          <option value="EB03">EB03</option>
          <option value="EB04">EB04</option>
          <option value="EB05">EB05</option>
          <option value="EB06">EB06</option>
          <option value="EB07">EB07</option>
        </select>
      </div>
     </div>
    <div class="form-group row">
      <label  class="col-2 col-form-label">Nama Kapal</label>
      <div class="col-10">
        <select class="form-control kt-select2" id="kt_select2_1_nama_kpl" name="param">
          <option value="PO II">PO II</option>
          <option value="PO III">PO III</option>
          <option value="PO V">PO V</option>
          <option value="PO VI">PO VI</option>
          <option value="PO VII">PO VII</option>
          <option value="PO VIII">PO VIII</option>
          <option value="PO IX">PO IX</option>
          <option value="PO X">PO X</option>
          <option value="PO XI">PO XI</option>
          <option value="PO XII">PO XII</option>
          <option value="PO XV">PO XV</option>
          <option value="PO XIX">PO XIX</option>
          <option value="PO XX">PO XX</option>
          <option value="PO XXI">PO XXI</option>
          <option value="PO XXII">PO XXII</option>
          <option value="PO XXIII">PO XXIII</option>
          <option value="PO XXV">PO XXV</option>
          <option value="PO XXVI">PO XXVI</option>
          <option value="PO XXVII">PO XXVII</option>
          <option value="PO XXIX">PO XXIX</option>
          <option value="SPT">SPT</option>
          <option value="PAMURADAN">PAMURADAN</option>
          <option value="JAYA UTAMA II">JAYA UTAMA II</option>
          <option value="YAN 01">YAN 01</option>
          <option value="EKA BAHANA">EKA BAHANA</option>
          <option value="JEFRY 88">JEFRY 88</option>
          <option value="PO XVI">PO XVI</option>
        </select>
      </div>
     </div>
     <div class="form-group row">
       <label class="col-form-label col-lg-3 col-sm-12 m-right-50">Jenis Kegiatan</label>
       <div class=" col-lg-4 col-md-9 col-sm-12">
         <select class="form-control kt-select2" id="kt_select2_1_jenis_kegiatan" name="param">
           <option value="AUDIT RUTIN">AUDIT RUTIN</option>
           <option value="AUDIT BUTA">AUDIT BUTA</option>
           <option value="STOCK OPNAME GUDANG KAPAL">STOCK OPNAME GUDANG KAPAL</option>
           <option value="STOCK OPNAME GUDANG CABANG BITUNG">STOCK OPNAME GUDANG CABANG BITUNG</option>
           <option value="STOCK OPNAME GUDANG CABANG SORONG">STOCK OPNAME GUDANG CABANG SORONG</option>
           <option value="STOCK OPNAME GUDANG CABANG KUPANG">STOCK OPNAME GUDANG CABANG KUPANG</option>
           <option value="STOCK OPNAME GUDANG CABANG TERNATE">STOCK OPNAME GUDANG CABANG TERNATE</option>
           <option value="STOCK OPNAME GUDANG CABANG TUAL">STOCK OPNAME GUDANG CABANG TUAL</option>
           <option value="STOCK OPNAME GUDANG CABANG AMBON">STOCK OPNAME GUDANG CABANG AMBON</option>
           <option value="STOCK OPNAME GUDANG CABANG BINTUNI">STOCK OPNAME GUDANG CABANG BINTUNI</option>
           <option value="STOCK OPNAME GUDANG MARGOMULYO">STOCK OPNAME GUDANG MARGOMULYO</option>
           <option value="AUDIT KAS CABANG SORONG">AUDIT KAS CABANG SORONG</option>
           <option value="AUDIT KAS CABANG BITUNG">AUDIT KAS CABANG BITUNG</option>
           <option value="AUDIT KAS CABANG KUPANG">AUDIT KAS CABANG KUPANG</option>
           <option value="AUDIT KAS CABANG TERNATE">AUDIT KAS CABANG TERNATE</option>
           <option value="AUDIT KAS CABANG BINTUNI">AUDIT KAS CABANG BINTUNI</option>
           <option value="AUDIT KAS CABANG AMBON">AUDIT KAS CABANG AMBON</option>
           <option value="AUDIT KAS CABANG TUAL">AUDIT KAS CABANG TUAL</option>
           <option value="PENGAWASAN BUNKER">PENGAWASAN BUNKER</option>
           <option value="PENGAWASAN LOADING">PENGAWASAN LOADING</option>
           <option value="PENGAWASAN OVERPUMPING">PENGAWASAN OVERPUMPING</option>
           <option value="PENGAWASAN PENGISIAN TANKI BBM SHIPYARD">PENGAWASAN PENGISIAN TANKI BBM SHIPYARD</option>
           <option value="LAIN-LAIN">LAIN-LAIN</option>
         </select>
       </div>
       <label class="col-form-label col-lg-3 col-sm-12 m-right-80">Lain-lain</label>
       <div class=" col-lg-4 col-md-9 col-sm-12">
         <input class="form-control" type="text" value="" id="example-number-input" placeholder="Ketik kegiatan lain-lain"/>
       </div>
     </div>
     <div class="form-group row">
       <label class="col-form-label col-lg-3 col-sm-12 m-right-50">Item Audit</label>
       <div class=" col-lg-4 col-md-9 col-sm-12">
         <select class="form-control kt-select2" id="kt_select2_1_item_audit" name="param">
           <option value="SOUNDING CARGO & BBM HARIAN">SOUNDING CARGO & BBM HARIAN</option>
           <option value="PENGECEKAN STOCK RFB, SEGEL STICKER">PENGECEKAN STOCK RFB, SEGEL STICKER</option>
           <option value="PENGECEKAN KESELURUHAN TANKI">PENGECEKAN KESELURUHAN TANKI</option>
           <option value="PENGECEKAN PEMAKAIAN JAM OLI DAN STOCK OLI">PENGECEKAN PEMAKAIAN JAM OLI DAN STOCK OLI</option>
           <option value="KONDISI KAPAL">KONDISI KAPAL</option>
           <option value="STOCK OPNAME GUDANG CABANG KUPANG">STOCK OPNAME GUDANG CABANG KUPANG</option>
           <option value="STOCK OPNAME GUDANG CABANG TERNATE">STOCK OPNAME GUDANG CABANG TERNATE</option>
           <option value="STOCK OPNAME GUDANG CABANG TUAL">STOCK OPNAME GUDANG CABANG TUAL</option>
           <option value="STOCK OPNAME GUDANG CABANG AMBON">STOCK OPNAME GUDANG CABANG AMBON</option>
           <option value="STOCK OPNAME GUDANG CABANG BINTUNI">STOCK OPNAME GUDANG CABANG BINTUNI</option>
           <option value="STOCK OPNAME GUDANG MARGOMULYO">STOCK OPNAME GUDANG MARGOMULYO</option>
           <option value="AUDIT KAS CABANG SORONG">AUDIT KAS CABANG SORONG</option>
           <option value="AUDIT KAS CABANG BITUNG">AUDIT KAS CABANG BITUNG</option>
           <option value="AUDIT KAS CABANG KUPANG">AUDIT KAS CABANG KUPANG</option>
           <option value="AUDIT KAS CABANG TERNATE">AUDIT KAS CABANG TERNATE</option>
           <option value="AUDIT KAS CABANG BINTUNI">AUDIT KAS CABANG BINTUNI</option>
           <option value="AUDIT KAS CABANG AMBON">AUDIT KAS CABANG AMBON</option>
           <option value="AUDIT KAS CABANG TUAL">AUDIT KAS CABANG TUAL</option>
         </select>
       </div>
       <label class="col-form-label col-lg-3 col-sm-12 m-right-80">Lain-lain</label>
       <div class=" col-lg-4 col-md-9 col-sm-12">
         <input class="form-control" type="text" value="" id="example-number-input" placeholder="Ketik item audit lain-lain"/>
       </div>
     </div>
     <div class="form-group">
      <label>Lampiran</label>
      <div></div>
        <div class="custom-file">
           <input type="file" class="custom-file-input"/>
           <label class="custom-file-label" for="customFile">Pilih Lampiran</label>
        </div>
        <div class="form-group m-top-20">
          <label>Kesimpulan</label>
          <textarea name="" rows="8" cols="80" class="form-control" placeholder="Ketik kesimpulan"></textarea>
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

@section('script')
<script type="text/javascript">
  $('#kt_select2_1_auditor, #kt_select2_1_nama_kpl, #kt_select2_1_jenis_kegiatan, #kt_select2_1_item_audit, #kt_select2_1_auditor').select2({
      placeholder: "Select a state"
  });
</script>
@endsection
