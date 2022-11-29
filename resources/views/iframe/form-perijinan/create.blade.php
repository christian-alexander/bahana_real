@extends('iframe.layouts.index')

@section('title')
    Form Perijinan
@endsection

@section('body')
  <form method="POST" action="{{ url('form-perijinan/doInput') }}" enctype="multipart/form-data">
    @csrf
    <div class="kt-portlet__body">

      @if (Session::has('success'))
        <div class="alert alert-success">{{ Session::get('success') }}</div>
      @endif
      @if (Session::has('danger'))
        <div class="alert alert-danger">{{ Session::get('danger') }}</div>
      @endif
      {{-- @if ($errors->any())
        <div class="alert alert-danger">
        <ul>
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
        </div>
      @endif --}}
      
      <div class="form-group row">
        <label for="nama_perusahaan" class="col-4 col-form-label">Nama Perusahaan</label>
        <div class="col-8">
          <input id="nama_perusahaan" name="nama_perusahaan" type="text" class="form-control" required="required">
          @if ($errors->has('nama_perusahaan'))
          <div class="invalid-feedback" style="display: block">{{$errors->first('nama_perusahaan')}}</div>
          @endif
        </div>
      </div>
      
      <div class="form-group row">
        <label for="pihak_kedua" class="col-4 col-form-label">Pihak Kedua</label>
        <div class="col-8">
          <input id="pihak_kedua" name="pihak_kedua" type="text" class="form-control" required="required">
          @if ($errors->has('pihak_kedua'))
          <div class="invalid-feedback" style="display: block">{{$errors->first('pihak_kedua')}}</div>
          @endif
        </div>
      </div>
      
      <div class="form-group row">
        <label for="jenis_perijinan_id" class="col-4 col-form-label">Jenis Perijinan</label>
        <div class="col-8">
          <select name="jenis_perijinan_id" class="form-control" required>
            @foreach ($jenis_perijinans as $jenis_perijinan)
              <option value="{{ $jenis_perijinan->id }}">{{ $jenis_perijinan->nama }}</option>
            @endforeach
          </select>
          @if ($errors->has('jenis_perijinan_id'))
          <div class="invalid-feedback" style="display: block">{{$errors->first('jenis_perijinan_id')}}</div>
          @endif
        </div>
      </div>

      <div class="form-group row">
        <label for="no_perijinan" class="col-4 col-form-label">No Perijinan</label>
        <div class="col-8">
          <input id="no_perijinan" name="no_perijinan" type="text" class="form-control" required="required">
          @if ($errors->has('no_perijinan'))
          <div class="invalid-feedback" style="display: block">{{$errors->first('no_perijinan')}}</div>
          @endif
        </div>
      </div>
      
      <div class="form-group row">
        <label for="start_berlaku" class="col-4 col-form-label">Masa Berlaku</label>
        <div class="col-8">
          <input id="start_berlaku" name="start_berlaku" placeholder="YYYY/MM/DD" type="date" class="form-control">
          @if ($errors->has('start_berlaku'))
          <div class="invalid-feedback" style="display: block">{{$errors->first('start_berlaku')}}</div>
          @endif
        </div>
      </div>
      
      <div class="form-group row">
        <label for="end_berlaku" class="col-4 col-form-label">Sampai</label>
        <div class="col-8">
          <input id="end_berlaku" name="end_berlaku" placeholder="YYYY/MM/DD" type="date" class="form-control">
          @if ($errors->has('end_berlaku'))
          <div class="invalid-feedback" style="display: block">{{$errors->first('end_berlaku')}}</div>
          @endif
        </div>
      </div>

      <div class="form-group row">
        <label for="posisi_dokumen" class="col-4 col-form-label">Posisi Dokumen</label>
        <div class="col-8">
          <input id="posisi_dokumen" name="posisi_dokumen" type="text" class="form-control" required="required">
          @if ($errors->has('posisi_dokumen'))
          <div class="invalid-feedback" style="display: block">{{$errors->first('posisi_dokumen')}}</div>
          @endif
        </div>
      </div>

      <div class="form-group row">
        <label for="nama_pic" class="col-4 col-form-label">Nama PIC</label>
        <div class="col-8">
          <input id="nama_pic" name="nama_pic" type="text" class="form-control" required="required">
          @if ($errors->has('nama_pic'))
          <div class="invalid-feedback" style="display: block">{{$errors->first('nama_pic')}}</div>
          @endif
        </div>
      </div>

      <div class="form-group row">
        <label for="no_hp" class="col-4 col-form-label">No HP</label>
        <div class="col-8">
          <input id="no_hp" name="no_hp" type="text" class="form-control" required="required">
          @if ($errors->has('no_hp'))
          <div class="invalid-feedback" style="display: block">{{$errors->first('no_hp')}}</div>
          @endif
        </div>
      </div>

      <div class="form-group row">
        <label for="email" class="col-4 col-form-label">Email</label>
        <div class="col-8">
          <input id="email" name="email" type="email" class="form-control" required="required">
          @if ($errors->has('email'))
          <div class="invalid-feedback" style="display: block">{{$errors->first('email')}}</div>
          @endif
        </div>
      </div>

      <div class="form-group row">
        <label for="jabatan" class="col-4 col-form-label">Jabatan</label>
        <div class="col-8">
          <input id="jabatan" name="jabatan" type="text" class="form-control" required="required">
          @if ($errors->has('jabatan'))
          <div class="invalid-feedback" style="display: block">{{$errors->first('jabatan')}}</div>
          @endif
        </div>
      </div>

      <div class="form-group row">
        <label for="attachment" class="col-4 col-form-label">Attachment</label>
        <div class="col-8">
          <input id="attachment" name="attachment[]" type="file" class="form-control" multiple>          
        </div>
      </div>
      
      <div class="form-group row">
        <label for="note" class="col-4 col-form-label">Note</label>
        <div class="col-8">
          <textarea id="note" name="note" cols="40" rows="5" class="form-control"></textarea>
          @if ($errors->has('note'))
          <div class="invalid-feedback" style="display: block">{{$errors->first('note')}}</div>
          @endif
        </div>
      </div>

    </div>

    <div class="kt-portlet__foot">
      <div class="kt-form__actions">
        <div style="text-align:center;">
          <button name="submit" type="submit" class="btn btn-primary">Submit</button>
        </div>
      </div>
    </div>

  </form>
@endsection
