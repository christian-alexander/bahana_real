@extends('iframe.layouts.index')

@section('title')
    Form Asuransi Mobil
@endsection

@section('body')
<form class="kt-form" action="" method="POST">
    @csrf
    <div class="kt-portlet__body">
      <div class="form-group">
        <label>Wilayah Operasional</label>
        <select class="form-control kt-select2"  id="kt_select2_1" name="param">
          <option value="Surabaya">Surabaya</option>
          <option value="Jakarta">Jakarta</option>
        </select>
      </div>
      <div class="form-group">
        <label>Keterangan Mobil</label>
        <input type="text" class="form-control" name="" value="" placeholder="Ketik keterangan mobil">
      </div>
      <div class="form-group">
        <label>Pengguna</label>
        <input type="text" class="form-control" name="" value="" placeholder="Ketik pengguna mobil">
      </div>
      <div class="form-group">
        <label>Asuransi</label>
        <input type="text" class="form-control" name="" value="" placeholder="Ketik asuransi mobil">
      </div>
      <div class="form-group">
        <label>Nilai Asuransi</label>
        <input type="number" class="form-control" name="" value="" placeholder="Ketik nilai asuransi mobil">
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
        <label>Posisi Dokumen Asli</label>
        <input type="text" class="form-control" name="" value="" placeholder="Ketik posisi dokumen asli">
      </div>
      <div class="form-group">
        <button type="button" class="btn btn-primary">Tambah</button>
      </div>
      <hr>
      <h4>Wilayah Operasional :</h4>
      <h4>Surabaya</h4>
      <div class="kt-section m-top-20">
          <div class="kt-section__content">
              <table id="tabledata" class="table table-bordered target-table ta-center display nowrap">
                  <thead>
                      <tr>
                        <th>Keterangan Mobil</th>
                        <th>Pengguna</th>
                        <th>Asuransi</th>
                        <th>Nilai Asuransi</th>
                        <th>Masa Berlaku</th>
                        <th>Posisi Dokumen Asli</th>
                      </tr>
                  </thead>
                  <tbody>
                      <tr>
                        <td>Innova – 1638 VZ</td>
                        <td>Office Pusat</td>
                        <td>Garda Otto</td>
                        <td>xxx</td>
                        <td>20 Juli 2020 - 12 Agustus 2020</td>
                        <td>xxx</td>
                      </tr>
                      <tr>
                        <td>Innova – 1638 VZ</td>
                        <td>Office Pusat</td>
                        <td>Garda Otto</td>
                        <td>xxx</td>
                        <td>20 Juli 2020 - 12 Agustus 2020</td>
                        <td>xxx</td>
                      </tr>
                      <tr>
                        <td>Innova – 1638 VZ</td>
                        <td>Office Pusat</td>
                        <td>Garda Otto</td>
                        <td>xxx</td>
                        <td>20 Juli 2020 - 12 Agustus 2020</td>
                        <td>xxx</td>
                      </tr>
                      <tr>
                        <td>Innova – 1638 VZ</td>
                        <td>Office Pusat</td>
                        <td>Garda Otto</td>
                        <td>xxx</td>
                        <td>20 Juli 2020 - 12 Agustus 2020</td>
                        <td>xxx</td>
                      </tr>
                      <tr>
                        <td>Innova – 1638 VZ</td>
                        <td>Office Pusat</td>
                        <td>Garda Otto</td>
                        <td>xxx</td>
                        <td>20 Juli 2020 - 12 Agustus 2020</td>
                        <td>xxx</td>
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
