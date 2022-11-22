@extends('iframe.layouts.index')

@section('title')
    Form Keluar Masuk Dokumen Brankas
@endsection

@section('body')
<form class="kt-form" action="" method="POST">
    @csrf
    <div class="kt-portlet__body">
      <div class="form-group">
        <label>Pilihan Dokumen</label>
        <select class="form-control kt-select2"  id="kt_select2_1" name="param">
          <option value="Perusahaan">Perusahaan</option>
          <option value="Aset Kendaraan">Aset Kendaraan</option>
          <option value="Aset Bangunan & Tanah">Aset Bangunan & Tanah</option>
          <option value="Sertifikat Perijinan">Sertifikat Perijinan</option>
          <option value="Perjanjian Sewa">Perjanjian Sewa</option>
        </select>
      </div>
      <div class="form-group">
        <label>Nama Dokumen</label>
        <input type="text" class="form-control" name="" value="" placeholder="Ketik nama dokumen">
      </div>
      <div class="form-group">
        <label>Format</label>
        <input type="text" class="form-control" name="" value="" placeholder="Ketik format">
      </div>
      <div class="form-group">
        <label>Lembar</label>
        <input type="text" class="form-control" name="" value="" placeholder="Ketik lembar dokumen">
      </div>
      <div class="form-group">
        <label>Tanggal Masuk Brankas</label>
        <input class="form-control" type="text" id="kt_datepicker_1" readonly placeholder="Select date" />
      </div>
      <div class="form-group">
        <label>Tanggal Keluar Brankas</label>
        <input class="form-control" type="text" id="kt_datepicker_1_validate" readonly placeholder="Select date" />
      </div>
      <div class="form-group">
        <label>Peminjam</label>
        <input type="text" class="form-control" name="" value="" placeholder="Ketik posisi dokumen aset">
      </div>
      <div class="form-group">
        <label>Keterangan Peminjam</label>
        <textarea name="" class="form-control" rows="5" cols="80" placeholder="Ketik keterangan peminjam"></textarea>
      </div>
      <div class="form-group">
        <label>Tanggal Kembali Brankas</label>
        <input class="form-control" type="text" id="kt_datepicker_2" readonly placeholder="Select date" />
      </div>
      <div class="form-group">
        <button type="button" class="btn btn-primary">Tambah</button>
      </div>
      <hr>
      <h4>PT. Bahana Line</h4>
      <div class="kt-section m-top-20">
          <div class="kt-section__content">
              <table id="tabledata" class="table table-bordered target-table ta-center display nowrap">
                  <thead>
                      <tr>
                        <th>No.</th>
                        <th>Nama Dokumen</th>
                        <th>Format</th>
                        <th>Lembar</th>
                        <th>Tgl. Masuk Brankas</th>
                        <th>Tgl. Keluar Brankas</th>
                        <th>Peminjam</th>
                        <th>Ket. Peminjam</th>
                        <th>Tgl. Kembali Brankas</th>
                      </tr>
                  </thead>
                  <tbody>
                      <tr>
                        <td>1</td>
                        <td>Akta Pendirian</td>
                        <td>Asli</td>
                        <td>1 Buku</td>
                        <td>5 Desember 2020</td>
                        <td>10 Desember 2020</td>
                        <td>Frans</td>
                        <td>Keperluan mengurus perijinan</td>
                        <td>23 Desember 2020</td>
                      </tr>
                      <tr>
                        <td>2</td>
                        <td>TDP</td>
                        <td>Asli</td>
                        <td>1 lbr</td>
                        <td>5 Desember 2020</td>
                        <td>10 Desember 2020</td>
                        <td>Frans</td>
                        <td>Keperluan mengurus perijinan</td>
                        <td>23 Desember 2020</td>
                      </tr>
                      <tr>
                        <td>2</td>
                        <td>TDP</td>
                        <td>Asli</td>
                        <td>1 lbr</td>
                        <td>5 Desember 2020</td>
                        <td>10 Desember 2020</td>
                        <td>Frans</td>
                        <td>Keperluan mengurus perijinan</td>
                        <td>23 Desember 2020</td>
                      </tr>
                      <tr>
                        <td>2</td>
                        <td>TDP</td>
                        <td>Asli</td>
                        <td>1 lbr</td>
                        <td>5 Desember 2020</td>
                        <td>10 Desember 2020</td>
                        <td>Frans</td>
                        <td>Keperluan mengurus perijinan</td>
                        <td>23 Desember 2020</td>
                      </tr>
                      <tr>
                        <td>2</td>
                        <td>TDP</td>
                        <td>Asli</td>
                        <td>1 lbr</td>
                        <td>5 Desember 2020</td>
                        <td>10 Desember 2020</td>
                        <td>Frans</td>
                        <td>Keperluan mengurus perijinan</td>
                        <td>23 Desember 2020</td>
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
