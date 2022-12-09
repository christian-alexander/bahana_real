@extends('iframe.layouts.index')

@section('title')
    Form Asuransi Jiwa Owner
@endsection

@section('body')
  <form method="POST" action="{{ url('form-asuransi-jiwa-owner/doInput') }}">
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

      <input type="hidden" name="user_id" value="{{ $user->id }}">
      <div class="form-group row">
        <label for="nama_pemilik" class="col-4 col-form-label">User Penginput</label>
        <div class="col-8">
          <input type="text" class="form-control" disabled value='{{ $user->name }}'>
        </div>
      </div>
      

      <div class="form-group row">
        <label for="nama_pemilik" class="col-4 col-form-label">Nama Pemilik</label>
        <div class="col-8">
          <input id="nama_pemilik" name="nama_pemilik" type="text" class="form-control" required="required">
          @if ($errors->has('nama_pemilik'))
          <div class="invalid-feedback" style="display: block">{{$errors->first('nama_pemilik')}}</div>
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
        <label for="tahapan_asuransi" class="col-4 col-form-label">Tahapan Asuransi</label>
        <div class="col-8">
          <input id="tahapan_asuransi" name="tahapan_asuransi" type="text" class="form-control" required="required">
          @if ($errors->has('tahapan_asuransi'))
          <div class="invalid-feedback" style="display: block">{{$errors->first('tahapan_asuransi')}}</div>
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
        <label for="jumlah_premi" class="col-4 col-form-label">Jumlah Premi</label>
        <div class="col-8">
          <input id="jumlah_premi" name="jumlah_premi" type="number" class="form-control" required="required">
          @if ($errors->has('jumlah_premi'))
          <div class="invalid-feedback" style="display: block">{{$errors->first('jumlah_premi')}}</div>
          @endif
        </div>
      </div>
      
      <div class="form-group row">
        <label for="jatuh_tempo" class="col-4 col-form-label">Jatuh Tempo</label>
        <div class="col-8">
          <input id="jatuh_tempo" name="jatuh_tempo" placeholder="YYYY/MM/DD" type="date" class="form-control">
          @if ($errors->has('jatuh_tempo'))
          <div class="invalid-feedback" style="display: block">{{$errors->first('jatuh_tempo')}}</div>
          @endif
        </div>
      </div>
      
      <div class="form-group row">
        <label for="tanggal_bayar" class="col-4 col-form-label">Tanggal Bayar</label>
        <div class="col-8">
          <input id="tanggal_bayar" name="tanggal_bayar" placeholder="YYYY/MM/DD" type="date" class="form-control">
          @if ($errors->has('tanggal_bayar'))
          <div class="invalid-feedback" style="display: block">{{$errors->first('tanggal_bayar')}}</div>
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
