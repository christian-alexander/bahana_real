@extends('iframe.layouts.index')

@section('title')
    Form Pengajuan Loading BBM
@endsection

@section('body')
<form class="kt-form" action="" method="POST">
  @csrf
  <div class="kt-portlet__body">
    <h4>Pengajuan Baru</h4>
    <hr>
    <div class="form-group row">
      <label  class="col-2 col-form-label">Supplier</label>
      <div class="col-10">
        <select class="form-control kt-select2" id="kt_select2_1_supplier" name="param">
            <option value="bl">BL</option>
            <option value="bol">BOL</option>
            <option value="po">PO</option>
        </select>
      </div>
     </div>
    <div class="form-group row">
      <label  class="col-2 col-form-label">Tanggal</label>
      <div class="col-10">
        <input class="form-control" type="text" id="kt_datepicker_1" readonly placeholder="Select date" />
      </div>
     </div>
     <div class="form-group row">
       <label  class="col-2 col-form-label">Wilayah</label>
       <div class="col-10">
         <select class="form-control kt-select2" id="kt_select2_1_wilayah" name="param">
             <option value="bl">Pilih wilayah yang mau loading</option>
             <option value="bl">Pilih wilayah yang mau loading</option>
             <option value="bl">Pilih wilayah yang mau loading</option>
         </select>
       </div>
      </div>
     <div class="form-group row">
       <label  class="col-2 col-form-label">Produk</label>
       <div class="col-10">
         <select class="form-control kt-select2" id="kt_select2_1_product" name="param">
             <option value="bl">Pilih produk</option>
             <option value="bl">Pilih produk</option>
             <option value="bl">Pilih produk</option>
         </select>
       </div>
      </div>
      <div class="form-group row">
        <label  class="col-2 col-form-label">Volume</label>
        <div class="col-10">
            <div class="input-group">
               <input type="number" class="form-control" placeholder="Ketik jumlah" aria-describedby="basic-addon2"/>
               <div class="input-group-append"><span class="input-group-text">Liter</span></div>
            </div>
          </div>
       </div>
       <div class="form-group row">
         <label  class="col-2 col-form-label">Harga</label>
         <div class="col-10">
           <input type="number" class="form-control" placeholder="Dimunculkan untuk akun tertentu"/>
         </div>
       </div>
       <div class="form-group">
           <label for="exampleTextarea">Nama Pelanggan SH</label>
           <input type="text" class="form-control" name="" value=""/>
       </div>
      <div class="form-group">
        <button type="button" class="btn btn-primary">Send Report</button>
      </div>
       <hr>
       <h4>Status Pengajuan Loading</h4>
       <hr>
       <div class="kt-section ta-center">
           <div class="kt-section__content">
               <table id="tabledata" class="table table-bordered target-table display nowrap">
                   <thead>
                       <tr>
                         <th>Tanggal Pengajuan</th>
                         <th>Wilayah</th>
                         <th>Qty Pengajuan</th>
                         <th>Nama SH</th>
                         <th>No LO</th>
                         <th>Qty LO</th>
                         <th>Tanggal Loading</th>
                         <th>No SPP</th>
                       </tr>
                   </thead>
                   <tbody>
                       <tr>
                         <td>1 Ags 2019</td>
                         <td>Surabaya</td>
                         <td>500KL</td>
                         <td>PT.Sinar Silau</td>
                         <td>12345676543</td>
                         <td>200 KL</td>
                         <td>2 Ags 2019</td>
                         <td>12345646</td>
                       </tr>
                       <tr>
                         <td>1 Ags 2019</td>
                         <td>Surabaya</td>
                         <td>500KL</td>
                         <td>PT.Sinar Silau</td>
                         <td>12345676543</td>
                         <td>200 KL</td>
                         <td>2 Ags 2019</td>
                         <td>12345646</td>
                       </tr>
                       <tr>
                         <td>1 Ags 2019</td>
                         <td>Surabaya</td>
                         <td>500KL</td>
                         <td>PT.Sinar Silau</td>
                         <td>12345676543</td>
                         <td>200 KL</td>
                         <td>2 Ags 2019</td>
                         <td>12345646</td>
                       </tr>
                       <tr>
                         <td>1 Ags 2019</td>
                         <td>Surabaya</td>
                         <td>500KL</td>
                         <td>PT.Sinar Silau</td>
                         <td>12345676543</td>
                         <td>200 KL</td>
                         <td>2 Ags 2019</td>
                         <td>12345646</td>
                       </tr>
                       <tr>
                         <td>1 Ags 2019</td>
                         <td>Surabaya</td>
                         <td>500KL</td>
                         <td>PT.Sinar Silau</td>
                         <td>12345676543</td>
                         <td>200 KL</td>
                         <td>2 Ags 2019</td>
                         <td>12345646</td>
                       </tr>
                       <!-- <tr class="no-data" style="display:none;">
                           <td colspan="8"><center>Tidak ada data</center></td>
                       </tr> -->
                   </tbody>
               </table>
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
$(document).ready(function() {
    $('#tabledata').DataTable( {
        "scrollX": true ,
        "scrollY":"200px",
        "scrollCollapse": true,
        "paging": false
    } );
  } );

  $('#kt_select2_1_satuan, #kt_select2_1_product, #kt_select2_1_wilayah, #kt_select2_1_supplier, #kt_select2_1_customer, #kt_select2_1_kapal, #kt_select2_1_wilayah_po').select2({
      placeholder: "Select a state"
  });
</script>
@endsection
