@extends('iframe.layouts.index')

@section('title')
    Form Tagihan
@endsection

@section('body')

<form class="kt-form" action="/form-tagihan/doInput" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="kt-portlet__body">

      <input type="hidden" name="user_id" value="{{ $d->id }}">
      <div class="form-group">
        <label for="nama_pemilik" class="col-4 col-form-label">User Penginput</label>
        <input type="text" class="form-control" disabled value='{{ $d->name }}'>
      </div>

      <div class="form-group">
        <label>Pilihan</label>
        <select class="form-control kt-select2"  id="kt_select2_1_pilihan" name="pilihan">
          <option value="Owner">Owner</option>
          <option value="Perusahaan">Perusahaan</option>
        </select>
      </div>

      <div class="form-group">
        <label>Tagihan</label>
        <select class="form-control kt-select2"  id="kt_select2_1_tagihan" name="tagihan">
          <option value="KK BCA">KK BCA</option>
          <option value="Listrik Graha Fam">Listrik Graha Fam</option>
          <option value="Halo Corp">Halo Corp</option>
          <option value="Telp & Inet">Telp & Inet</option>
        </select>
      </div>

      <div class="form-group">
        <label>Jatuh Tempo</label>
          <input id="jatuh_tempo" name="jatuh_tempo" placeholder="YYYY/MM/DD" type="date" class="form-control kt-select2">
          @if ($errors->has('jatuh_tempo'))
          <div class="invalid-feedback" style="display: block">{{$errors->first('jatuh_tempo')}}</div>
          @endif
      </div>

      <div class="form-group">
        <label>Tanggal Serah Kasir</label>
        <input type="text" class="form-control" name="tanggal_serah_kasir" value="" placeholder="Ketik metode dan tanggal serah">
        @if ($errors->has('tanggal_serah_kasir'))
        <div class="invalid-feedback" style="display: block">{{$errors->first('tanggal_serah_kasir')}}</div>
        @endif
      </div>

      <div class="form-group">
       <label>Attachment</label>
       <div></div>
         <div class="custom-file">
          <input type="file" class="custom-file-input" name="attachment"/>
          <label class="custom-file-label" for="customFile">Pilih attachment</label>
         </div>
         @if ($errors->has('attachment'))
         <div class="invalid-feedback" style="display: block">{{$errors->first('attachment')}}</div>
         @endif
      </div>

      {{-- <div class="form-group">
        <button type="button" class="btn btn-primary">Tambah</button>
      </div> --}}


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
                    @foreach ($data as $d)
                    <tr>
                      <td>{{ $d['tagihan']}}</td>
                      <td>{{ $d['jatuh_tempo']}}</td>
                      <td>{{ $d['tanggal_serah_kasir']}}</td>
                      <td>{{ $d['attachment']}}</td>
                    </tr> 
                    @endforeach
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
