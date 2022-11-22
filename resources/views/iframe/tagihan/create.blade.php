@extends('iframe.layouts.index')

@section('title')
    Form Tagihan
@endsection

@section('body')
<form class="kt-form" action="" method="POST">
    @csrf
    <div class="kt-portlet__body">
      <div class="form-group">
        <label>Pilihan</label>
        <select class="form-control kt-select2"  id="kt_select2_1_pilihan" name="param">
          <option value="Owner">Owner</option>
          <option value="Perusahaan">Perusahaan</option>
        </select>
      </div>
      <div class="form-group">
        <label>Tagihan</label>
        <select class="form-control kt-select2"  id="kt_select2_1_tagihan" name="param">
          <option value="KK BCA">KK BCA</option>
          <option value="Listrik Graha Fam">Listrik Graha Fam</option>
          <option value="Halo Corp">Halo Corp</option>
          <option value="Telp & Inet">Telp & Inet</option>
        </select>
      </div>
      <div class="form-group">
        <label>Jatuh Tempo</label>
        <input class="form-control" type="text" id="kt_datepicker_1" readonly placeholder="Select date" />
      </div>
      <div class="form-group">
        <label>Tanggal Serah Kasir</label>
        <input type="text" class="form-control" name="" value="" placeholder="Ketik metode dan tanggal serah">
      </div>
      <div class="form-group">
       <label>Attachment</label>
       <div></div>
         <div class="custom-file">
          <input type="file" class="custom-file-input"/>
          <label class="custom-file-label" for="customFile">Pilih attachment</label>
         </div>
      </div>
      <div class="form-group">
        <button type="button" class="btn btn-primary">Tambah</button>
      </div>
      <hr>
      <h4>Tagihan Owner</h4>
      <div class="kt-section m-top-20">
          <div class="kt-section__content">
              <table id="tabledata" class="table table-bordered target-table ta-center display nowrap">
                  <thead>
                      <tr>
                        <th>Tagihan.</th>
                        <th>Jatuh Tempo</th>
                        <th>Tanggal Serah Kasir</th>
                        <th>Attachment</th>
                      </tr>
                  </thead>
                  <tbody>
                      <tr>
                        <td>KK BCA</td>
                        <td>8 Juli 2020</td>
                        <td>Transfer 1 Juli 2019</td>
                        <td>....</td>
                      </tr>
                      <tr>
                        <td>Listrik Graha Fam</td>
                        <td>10 Juli 2020</td>
                        <td>Autodebt 10 Juli 2019</td>
                        <td>....</td>
                      </tr>
                      <!-- <tr class="no-data">
                          <td colspan="5"><center>Tidak ada data</center></td>
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

$('#kt_select2_1_tagihan, #kt_select2_1_pilihan').select2({
    placeholder: "Select a state"
});
</script>
@endsection
