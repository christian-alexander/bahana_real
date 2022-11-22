@extends('iframe.layouts.index')

@section('title')
    Form Asuransi Jiwa Owner
@endsection

@section('body')
<form class="kt-form" action="" method="POST">
    @csrf
    <div class="kt-portlet__body">
      <div class="form-group">
        <label>Nama</label>
        <input type="text" class="form-control" name="" value="" placeholder="Ketik nama">
      </div>
      <div class="form-group">
        <label>Asuransi</label>
        <input type="text" class="form-control" name="" value="" placeholder="Ketik asuransi jiwa">
      </div>
      <div class="form-group">
        <label>Tahapan & Nilai</label>
        <input type="text" class="form-control" name="" value="" placeholder="Ketik tahapan dan nilai">
      </div>
      <div class="form-group">
        <label>Nilai Asuransi</label>
        <input type="number" class="form-control" name="" value="" placeholder="Ketik nilai asuransi">
      </div>
      <div class="form-group">
        <label>Jumlah Premi</label>
        <input type="number" class="form-control" name="" value="" placeholder="Ketik jumlah premi">
      </div>
      <div class="form-group">
        <label>Jatuh Tempo</label>
        <input class="form-control" type="text" id="kt_datepicker_1" readonly placeholder="Select date" />
      </div>
      <div class="form-group">
        <label>Tanggal & Bayar</label>
        <input class="form-control" type="text" id="kt_datepicker_1_validate" readonly placeholder="Select date" />
      </div>
      <div class="form-group">
        <button type="button" class="btn btn-primary">Tambah</button>
      </div>
      <hr>
      <h4>Asuransi Jiwa Owner</h4>
      <div class="kt-section m-top-20">
          <div class="kt-section__content">
              <table id="tabledata" class="table table-bordered ta-center display nowrap">
                  <thead>
                      <tr>
                        <th>Nama</th>
                        <th>Asuransi</th>
                        <th>Tahapan & Nilai</th>
                        <th>Jumlah Premi</th>
                        <th>Jatuh Tempo</th>
                        <th>Pembayaran & Tanggal</th>
                      </tr>
                  </thead>
                  <tbody>
                      <tr>
                        <td>Freddy Santoso</td>
                        <td>Alianz</td>
                        <td>xxx</td>
                        <td>xxx</td>
                        <td>20 Juli 2020 & 12 Agustus 2020</td>
                        <td>20 Juli 2020 & 12 Agustus 2020</td>
                      </tr>
                      <tr>
                        <td>Freddy Santoso</td>
                        <td>Alianz</td>
                        <td>xxx</td>
                        <td>xxx</td>
                        <td>20 Juli 2020 & 12 Agustus 2020</td>
                        <td>20 Juli 2020 & 12 Agustus 2020</td>
                      </tr>
                      <tr>
                        <td>Freddy Santoso</td>
                        <td>Alianz</td>
                        <td>xxx</td>
                        <td>xxx</td>
                        <td>20 Juli 2020 & 12 Agustus 2020</td>
                        <td>20 Juli 2020 & 12 Agustus 2020</td>
                      </tr>
                      <tr>
                        <td>Freddy Santoso</td>
                        <td>Alianz</td>
                        <td>xxx</td>
                        <td>xxx</td>
                        <td>20 Juli 2020 & 12 Agustus 2020</td>
                        <td>20 Juli 2020 & 12 Agustus 2020</td>
                      </tr>
                      <tr>
                        <td>Freddy Santoso</td>
                        <td>Alianz</td>
                        <td>xxx</td>
                        <td>xxx</td>
                        <td>20 Juli 2020 & 12 Agustus 2020</td>
                        <td>20 Juli 2020 & 12 Agustus 2020</td>
                      </tr>
                      <tr>
                        <td>Freddy Santoso</td>
                        <td>Alianz</td>
                        <td>xxx</td>
                        <td>xxx</td>
                        <td>20 Juli 2020 & 12 Agustus 2020</td>
                        <td>20 Juli 2020 & 12 Agustus 2020</td>
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
