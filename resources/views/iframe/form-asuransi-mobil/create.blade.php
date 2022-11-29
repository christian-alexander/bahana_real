@extends('iframe.layouts.index')

@section('title')
    Form Asuransi Mobil
@endsection

@section('body')
  <form method="POST" action="{{ url('form-asuransi-mobil/doInput') }}">
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
        <label for="wilayah_operasional" class="col-4 col-form-label">Wilayah Operasional</label>
        <div class="col-8">
          <input id="wilayah_operasional" name="wilayah_operasional" type="text" class="form-control" required="required">
          @if ($errors->has('wilayah_operasional'))
          <div class="invalid-feedback" style="display: block">{{$errors->first('wilayah_operasional')}}</div>
          @endif
        </div>
      </div>
      
      <div class="form-group row">
        <label for="keterangan_mobil" class="col-4 col-form-label">Keterangan Mobil</label>
        <div class="col-8">
          <input id="keterangan_mobil" name="keterangan_mobil" type="text" class="form-control" required="required">
          @if ($errors->has('keterangan_mobil'))
          <div class="invalid-feedback" style="display: block">{{$errors->first('keterangan_mobil')}}</div>
          @endif
        </div>
      </div>

      <div class="form-group row">
        <label for="pengguna" class="col-4 col-form-label">Pengguna</label>
        <div class="col-8">
          <input id="pengguna" name="pengguna" type="text" class="form-control" required="required">
          @if ($errors->has('pengguna'))
          <div class="invalid-feedback" style="display: block">{{$errors->first('pengguna')}}</div>
          @endif
        </div>
      </div>
      
      <div class="form-group row">
        <label for="asuransi" class="col-4 col-form-label">Asuransi</label>
        <div class="col-8">
          <input id="asuransi" name="asuransi" type="text" class="form-control" required="required">
          @if ($errors->has('asuransi'))
          <div class="invalid-feedback" style="display: block">{{$errors->first('asuransi')}}</div>
          @endif
        </div>
      </div>
      
      <div class="form-group row">
        <label for="nilai_asuransi" class="col-4 col-form-label">Nilai Asuransi</label>
        <div class="col-8">
          <input id="nilai_asuransi" name="nilai_asuransi" type="number" class="form-control" required="required">
          @if ($errors->has('nilai_asuransi'))
          <div class="invalid-feedback" style="display: block">{{$errors->first('nilai_asuransi')}}</div>
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
        <label for="posisi_dokumen_asli" class="col-4 col-form-label">Posisi Dokumen</label>
        <div class="col-8">
          <input id="posisi_dokumen_asli" name="posisi_dokumen_asli" type="text" class="form-control" required="required">
          @if ($errors->has('posisi_dokumen_asli'))
          <div class="invalid-feedback" style="display: block">{{$errors->first('posisi_dokumen_asli')}}</div>
          @endif
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
