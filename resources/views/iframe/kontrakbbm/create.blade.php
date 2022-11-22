@extends('iframe.layouts.index')

@section('title')
    Form Kontrak BBM
@endsection

@section('body')
<form class="kt-form" action="" method="POST">
    @csrf
    <div class="kt-portlet__body">
      <div class="form-group">
        <label>Perusahaan</label>
        <select class="form-control kt-select2"  id="kt_select2_1" name="param">
          <option value="ptpertamina">PT.PERTAMINA</option>
          <option value="patraniaga">PT.PERTAMINA PATRA NIAGA</option>
          <option value="transkontinental">PT.PERTAMINA TRANS KONTINENTAL</option>
          <option value="meratus">MERATUS</option>
          <option value="antam">ANTAM</option>
          <option value="poltek">POLTEKPEL (KRI)</option>
          <option value="pln">PLN</option>
        </select>
      </div>
      <div class="form-group">
        <label>Judul Project</label>
        <input type="text" class="form-control" name="" value="">
      </div>
      <div class="form-group">
        <label>Jenis Kontrak</label>
        <input type="text" class="form-control" name="" value="">
      </div>
      <div class="form-group">
        <label>No. Kontrak</label>
        <input type="text" class="form-control" name="" value="">
      </div>
      <div class="form-group row">
        <label class="col-form-label col-lg-3 col-sm-12 m-right-50">Masa Berlaku</label>
        <div class=" col-lg-4 col-md-9 col-sm-12">
          <input class="form-control" type="text" id="kt_datepicker_1" readonly placeholder="Select date" />
        </div>
        <label class="col-form-label col-lg-3 col-sm-12 m-right-80">Sampai</label>
        <div class=" col-lg-4 col-md-9 col-sm-12">
          <input class="form-control" type="text" id="kt_datepicker_1_validate" readonly placeholder="Select date" />
        </div>
      </div>
      <div class="form-group">
        <label>Nilai Kontrak</label>
        <input type="text" class="form-control" name="" value="">
      </div>
      <h4>Info PIC</h4>
      <div class="kt-section m-top-20">
        <div class="form-group">
          <label>Nama</label>
          <input type="text" class="form-control" name="" value="">
        </div>
        <div class="form-group">
          <label>Nomor HP</label>
          <input type="text" class="form-control" name="" value="">
        </div>
        <div class="form-group">
          <label>Email</label>
          <input type="email" class="form-control" name="" value="">
        </div>
        <div class="form-group">
          <label>Jabatan</label>
          <input type="text" class="form-control" name="" value="">
        </div>
        <div class="form-group">
          <label>Department</label>
          <input type="text" class="form-control" name="" value="">
        </div>
        <div class="form-group">
          <button type="button" class="btn btn-primary">Tambah</button>
        </div>
      </div>
      <h4>Judul Project</h4>
      <div class="kt-section m-top-20">
          <div class="kt-section__content">
              <table id="tabledata" class="table table-bordered target-table ta-center display nowrap">
                  <thead>
                      <tr>
                          <th>Jenis Kontrak</th>
                          <th>No. Kontrak</th>
                          <th>Masa Berlaku</th>
                          <th>Nilai Kontrak</th>
                          <th>PIC-Jabatan-Department</th>
                      </tr>
                  </thead>
                  <tbody>
                      <tr>
                          <td>Induk</td>
                          <td>XXX/XXX/XXX</td>
                          <td>20 Juli 2020 - 12 Agustus 2020</td>
                          <td>....</td>
                          <td>....</td>
                      </tr>
                      <tr>
                          <td>Induk</td>
                          <td>XXX/XXX/XXX</td>
                          <td>20 Juli 2020 - 12 Agustus 2020</td>
                          <td>....</td>
                          <td>....</td>
                      </tr>
                      <tr>
                          <td>Induk</td>
                          <td>XXX/XXX/XXX</td>
                          <td>20 Juli 2020 - 12 Agustus 2020</td>
                          <td>....</td>
                          <td>....</td>
                      </tr>
                      <tr>
                          <td>Induk</td>
                          <td>XXX/XXX/XXX</td>
                          <td>20 Juli 2020 - 12 Agustus 2020</td>
                          <td>....</td>
                          <td>....</td>
                      </tr>
                      <tr>
                          <td>Induk</td>
                          <td>XXX/XXX/XXX</td>
                          <td>20 Juli 2020 - 12 Agustus 2020</td>
                          <td>....</td>
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
</script>
@endsection
