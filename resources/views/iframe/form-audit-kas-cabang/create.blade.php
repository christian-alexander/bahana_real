@extends('iframe.layouts.index')

@section('title')
    Form Audit Kas Cabang
@endsection

@section('body')

    <form method="post" action="/form-audit-kas-cabang/create" enctype="multipart/form-data">
        @csrf
        @php
            $current_timestamp = Carbon\Carbon::now();
        @endphp
        <div class="kt-portlet__body">

            <input id="start" name="start_at" placeholder="volume" type="hidden" class="form-control" value="{{$current_timestamp}}">

            <label for="no-form" class="col-4 col-form-label">No Form</label>
            <div class="form-group"> 
                <input id="no-form" name="no_form" placeholder="No Form" type="text" class="form-control">
                @if ($errors->has('no_form'))
                    <div class="invalid-feedback" style="display: block">{{$errors->first('no_form')}}</div>
                @endif
            </div>

            <label for="date" class="col-4 col-form-label">Tanggal</label>
            <div class="form-group"> 
                <input type="hidden" name="tanggal" value="{{ Carbon\Carbon::now()->translatedFormat('Y-m-d') }}">
                <input type="text" class='form-control' value="{{ Carbon\Carbon::now()->translatedFormat('d F Y') }}" disabled>
            </div>

            <label for="auditor" class="col-4 col-form-label"> Nama Auditor</label>
            <div class="form-group"> 
                <input type="hidden" name="users_id" value="{{ $user->id }}">
                <input type="text" value='{{ $user->name }}' class='form-control' disabled>
            </div>

            <label for="lokasi-cabang" class="col-4 col-form-label">Lokasi Cabang</label>
            <div class="form-group"> 
                <select id="lokasi-cabang" name="cabang_id" class="custom-select">
                    @foreach ($offices as $office)
                        <option value="{{ $office->id }}">{{ $office->name }}</option>
                    @endforeach
                </select>
            </div> 

            <label for="posisi" class="col-4 col-form-label">Posisi</label>
            <div class="form-group"> 
                <textarea id="posisi" name="posisi" cols="40" rows="5" class="form-control"></textarea>
                @if ($errors->has('posisi'))
                    <div class="invalid-feedback" style="display: block">{{$errors->first('posisi')}}</div>
                @endif
            </div>

            <label for="catatan" class="col-4 col-form-label">Catatan</label>
            <div class="form-group">
                <textarea id="catatan" name="catatan" cols="40" rows="5" class="form-control"></textarea>
                @if ($errors->has('catatan'))
                    <div class="invalid-feedback" style="display: block">{{$errors->first('catatan')}}</div>
                @endif
            </div>

            <label for="text1" class="col-4 col-form-label">Upload Foto</label>
            <div class="form-group"> 
                <div class="input-group">
                    <input id="text1" name="foto" type="file" class="form-control"> 
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <i class="fa fa-photo"></i>
                        </div>
                    </div>
                </div>
                @if ($errors->has('foto'))
                    <div class="invalid-feedback" style="display: block">{{$errors->first('foto')}}</div>
                @endif
            </div>

            <label for="temuan" class="col-4 col-form-label">Temuan</label>
            <div class="form-group"> 
                <textarea id="temuan" name="temuan" cols="40" rows="5" class="form-control"></textarea>
                {{-- <button name="tambah-temuan" type="submit" class="btn btn-primary" style="margin-top: 5px;">+</button> --}}
                @if ($errors->has('temuan'))
                    <div class="invalid-feedback" style="display: block">{{$errors->first('temuan')}}</div>
                @endif
            </div>

            <div id="signature-pad" class="signature-pad" style="height: 250px;border: none;box-shadow: none;padding: 0">
                <label for="ttd" class="col-4 col-form-label">Kolom TTD</label>
                <div class="signature-pad--body">
                    <canvas style="width: 100%;height: 100%;border: solid 1px #ccc;"></canvas>
                </div>
                <textarea id="signature64" name="ttd" style="display: none"></textarea>
                <button type="button" id="clear" class="btn btn-primary btn-sm form-control" data-action="clear">Bersihkan</button>
                @if ($errors->has('ttd'))
                    <div class="invalid-feedback" style="display: block">{{$errors->first('ttd')}}</div>
                @endif
            </div>

            <div class="form-group">
                <button name="save-as" type="button" class="btn btn-primary">Save As</button>
                <button type="button" class="btn btn-primary" onclick="get_time_now()">Start</button>
                <button name="stop" type="submit" class="btn btn-primary" id='submitBtn'>Simpan Laporan</button>
            </div>
        </div>
    </form>

@endsection


@section('script')
    
    <script>
        function get_time_now(){
            alert("waktu pengisian form telah dimulai");
            let ms = Math.floor(Date.now() / 1000);
            document.getElementById("start_at").value = ms;
        }
    </script>

        @if (session()->has('success'))
            <script>
                alert("{{ session()->get('success') }}");
            </script>
        @endif
@endsection
