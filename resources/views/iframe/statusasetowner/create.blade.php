@extends('iframe.layouts.index')

@section('title')
    Form Status Aset Owner
@endsection

@section('body')
<form class="kt-form" action="" method="POST">
    @csrf
    <div class="kt-portlet__body">
      <div class="form-group">
        <label>Wilayah Aset</label>
        <select class="form-control kt-select2"  id="kt_select2_1" name="param">
          <option value="Kotamadya Surabaya">Kotamadya Surabaya</option>
          <option value="Wilayah Aset">Wilayah Aset</option>
          <option value="Wilayah Aset">Wilayah Aset</option>
        </select>
      </div>
      <div class="form-group">
        <label>Nomor Sertifikat</label>
        <input type="text" class="form-control" name="" value="" placeholder="Ketik nomor aset">
      </div>
      <div class="form-group">
        <label>Nama Aset/Letak</label>
        <input type="text" class="form-control" name="" value="" placeholder="Ketik nama aset/letak">
      </div>
      <div class="form-group">
        <label>Luas</label>
        <input type="number" class="form-control" name="" value="" placeholder="Ketik luas aset">
      </div>
      <div class="form-group">
        <label>Atas Nama</label>
        <input type="text" class="form-control" name="" value="" placeholder="Ketik atas nama aset">
      </div>
      <div class="form-group">
        <label>Posisi Dokumen</label>
        <input type="text" class="form-control" name="" value="" placeholder="Ketik posisi dokumen aset">
      </div>
      <div class="form-group row">
        <label class="col-form-label col-lg-3 col-sm-12 m-right-50">Tgl Masuk</label>
        <div class=" col-lg-4 col-md-9 col-sm-12">
          <input class="form-control" type="text" id="kt_datepicker_1" readonly placeholder="Select date" />
        </div>
        <label class="col-form-label col-lg-3 col-sm-12 m-right-70">Tgl Keluar</label>
        <div class=" col-lg-4 col-md-9 col-sm-12">
          <input class="form-control" type="text" id="kt_datepicker_1_validate" readonly placeholder="Select date" />
        </div>
      </div>
      <div class="form-group">
        <label>Tahun</label>
        <input type="text" class="form-control" name="" value="" placeholder="Ketik tahun keluar dan masuk dokumen">
      </div>
      <div class="form-group">
        <button type="button" class="btn btn-primary">Tambah</button>
      </div>
      <hr>
      <h4>Kotamadya Surabaya</h4>
      <div class="kt-section m-top-20">
          <div class="kt-section__content">
              <table id="tabledata" class="table table-bordered target-table ta-center display nowrap">
                  <thead>
                      <tr>
                        <th>No.</th>
                        <th>Nomor Sertifikat</th>
                        <th>Nama Aset/Letak</th>
                        <th>Luas</th>
                        <th>Atas Nama</th>
                        <th>Pembayaran & Tanggal</th>
                        <th>Posisi Dok. & Tgl Masuk/Keluar</th>
                        <th>Tahun</th>
                      </tr>
                  </thead>
                  <tbody>
                      <tr>
                        <td>1</td>
                        <td>SHM : 1321</td>
                        <td>Desa  Dukuh Pakis, Kec. Dukuh Pakis - Surabaya (villa bukit mas r-8)</td>
                        <td>xxx</td>
                        <td>513 M²</td>
                        <td>Tresia Tansia</td>
                        <td>Bank Mandiri - 20 Juli 2020/12 Agustus 2020</td>
                        <td>2002</td>
                      </tr>
                      <tr>
                        <td>2</td>
                        <td>SHGB : 101</td>
                        <td>Ruko Laksda M.Nasir No. 29 B. 11 - Surabaya</td>
                        <td>xxx</td>
                        <td>66 M²</td>
                        <td>Freddy Soenjoyo</td>
                        <td>Brankas P.Tino - 20 Juli 2020/12 Agustus 2020</td>
                        <td>2002</td>
                      </tr>
                      <tr>
                        <td>2</td>
                        <td>SHGB : 101</td>
                        <td>Ruko Laksda M.Nasir No. 29 B. 11 - Surabaya</td>
                        <td>xxx</td>
                        <td>66 M²</td>
                        <td>Freddy Soenjoyo</td>
                        <td>Brankas P.Tino - 20 Juli 2020/12 Agustus 2020</td>
                        <td>2002</td>
                      </tr>
                      <tr>
                        <td>2</td>
                        <td>SHGB : 101</td>
                        <td>Ruko Laksda M.Nasir No. 29 B. 11 - Surabaya</td>
                        <td>xxx</td>
                        <td>66 M²</td>
                        <td>Freddy Soenjoyo</td>
                        <td>Brankas P.Tino - 20 Juli 2020/12 Agustus 2020</td>
                        <td>2002</td>
                      </tr>
                      <tr>
                        <td>2</td>
                        <td>SHGB : 101</td>
                        <td>Ruko Laksda M.Nasir No. 29 B. 11 - Surabaya</td>
                        <td>xxx</td>
                        <td>66 M²</td>
                        <td>Freddy Soenjoyo</td>
                        <td>Brankas P.Tino - 20 Juli 2020/12 Agustus 2020</td>
                        <td>2002</td>
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
