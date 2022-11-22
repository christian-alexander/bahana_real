@extends('iframe.layouts.index')

@section('title')
    Form Permintaan Tiket
@endsection

@section('body')
<form class="kt-form" action="" method="POST">
    @csrf
    <div class="kt-portlet__body">
      <div class="kt-section">
        <h4>Detail Penerbangan</h4>
        <br/>
        <div class="form-group">
          <label>Nama Penumpang(Sesuai KTP)</label>
          <input type="text" class="form-control" name="" value="" placeholder="Ketik nama penumpang">
        </div>
        <div class="form-group">
          <label>Tanggal Keberangkatan</label>
          <input class="form-control" type="text" id="kt_datepicker_1" readonly placeholder="Select date" />
        </div>
        <div class="form-group">
          <label>Rute</label>
          <input type="text" class="form-control" name="" value="" placeholder="Ketik rute">
        </div>
        <div class="form-group">
          <label>Note</label>
          <textarea class="form-control" name="" rows="5" cols="80" placeholder="Jika ada permintaan jam yang spesifik (karena kebutuhan pekerjaan), bisa dituliskan dalam note."></textarea>
        </div>
      </div>
      <div class="form-group">
        <label>Kode Booking</label>
        <input type="text" class="form-control" name="" value="" placeholder="Ketik kode booking">
      </div>
      <div class="form-group">
        <label>Otorisasi Kode Booking</label>
        <input type="text" class="form-control" name="" value="" placeholder="Ketik otorisasi kode booking">
      </div>
      <div class="form-group">
        <label>Keterangan Penolakan Otorisasi Kode Booking</label>
        <textarea class="form-control" name="" rows="5" cols="80" placeholder="Ketik keterangan penolakan"></textarea>
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
      <h4>Permintaan Tiket</h4>
      <div class="kt-section m-top-20">
          <div class="kt-section__content">
              <table id="tabledata" class="table table-bordered target-table ta-center display nowrap">
                  <thead>
                      <tr>
                        <th>Pemesan</th>
                        <th>Detail Penerbangan</th>
                        <th>Kode Booking</th>
                        <th>Otorisasi Kode Booking</th>
                        <th>Attachment</th>
                      </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>Husein</td>
                      <td>XXXXXX</td>
                      <td>XXXXXX</td>
                      <td>XXXXX</td>
                      <td>XXXXX</td>
                    </tr>
                    <tr>
                      <td>Husein</td>
                      <td>XXXXXX</td>
                      <td>XXXXXX</td>
                      <td>XXXXX</td>
                      <td>XXXXX</td>
                    </tr>
                    <tr>
                      <td>Husein</td>
                      <td>XXXXXX</td>
                      <td>XXXXXX</td>
                      <td>XXXXX</td>
                      <td>XXXXX</td>
                    </tr>
                    <tr>
                      <td>Husein</td>
                      <td>XXXXXX</td>
                      <td>XXXXXX</td>
                      <td>XXXXX</td>
                      <td>XXXXX</td>
                    </tr>
                    <tr>
                      <td>Husein</td>
                      <td>XXXXXX</td>
                      <td>XXXXXX</td>
                      <td>XXXXX</td>
                      <td>XXXXX</td>
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
