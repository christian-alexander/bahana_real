@extends('iframe.layouts.index')

@section('title')
    Form Audit Tanki
@endsection

@section('body')
    
    <form method="post" action='/form-audit-tanki/create' enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="start_at" value="{{ time() }}">

        <div class="kt-portlet__body">
            
            <div class="form-group"> 
                <label for="no-form" class="col-4 col-form-label">No Form</label>
                <input id="no-form" name="no_form" placeholder="No Form" type="text" class="form-control">
                @if ($errors->has('no_form'))
                    <div class="invalid-feedback" style="display: block">{{$errors->first('no_form')}}</div>
                @endif
            </div>

            <div class="form-group"> 
                <label for="date" class="col-4 col-form-label">Tanggal</label>
                <input type="hidden" name="tanggal" value="{{ Carbon\Carbon::now()->translatedFormat('Y-m-d') }}">
                <input type="text" class='form-control' value="{{ Carbon\Carbon::now()->translatedFormat('d F Y') }}" disabled>
            </div>

            <div class="form-group"> 
                <label for="auditor" class="col-4 col-form-label"> Nama Auditor</label>
                <input type="hidden" name="user_id" value="{{ $user->id }}">
                <input type="text" value='{{ $user->name }}' class='form-control' disabled>
            </div>

            <div class="form-group"> 
                <label for="office_id" class="col-4 col-form-label">Nama Kapal</label>
                <select id="office_id" name="office_id" class="custom-select">
                    @foreach ($offices as $office)
                        <option value="{{ $office->id }}">{{ $office->name }}</option>
                    @endforeach
                </select>
                @if ($errors->has('office_id'))
                    <div class="invalid-feedback" style="display: block">{{$errors->first('office_id')}}</div>
                @endif
            </div>

            <div class="form-group"> 
                <label for="posisi" class="col-4 col-form-label">Posisi</label>
                <textarea id="posisi" name="posisi" cols="40" rows="5" class="form-control"></textarea>
                @if ($errors->has('posisi'))
                    <div class="invalid-feedback" style="display: block">{{$errors->first('posisi')}}</div>
                @endif
            </div>

            <div class="form-group">
                <label for="catatan" class="col-4 col-form-label">Catatan</label>
                <textarea id="catatan" name="catatan" cols="40" rows="5" class="form-control"></textarea>
                @if ($errors->has('catatan'))
                    <div class="invalid-feedback" style="display: block">{{$errors->first('catatan')}}</div>
                @endif
            </div>

            <div class="form-group"> 
                <label for="foto" class="col-4 col-form-label">Upload Foto</label>
                <input type="file" id='foto' name="foto" class="form-control" accept="image/*">
                @if ($errors->has('foto'))
                    <div class="invalid-feedback" style="display: block">{{$errors->first('foto')}}</div>
                @endif
            </div>

            <div class="form-group"> 
                <label for="temuan" class="col-4 col-form-label">Temuan</label>
                <textarea id="temuan" name="temuan" cols="40" rows="5" class="form-control"></textarea>
                @if ($errors->has('temuan'))
                    <div class="invalid-feedback" style="display: block">{{$errors->first('temuan')}}</div>
                @endif
            </div>

            <div id="signature-pad" class="signature-pad" style="height: 250px;border: none;box-shadow: none;padding: 0">
                <label for="ttd" class="col-4 col-form-label">Kolom Tanda Tangan</label>
                <div class="signature-pad--body">
                    <canvas style="width: 100%;height: 100%;border: solid 1px #ccc;"></canvas>
                </div>
                <textarea id="signature64" name="ttd" style="display: none"></textarea>
                <button type="button" id="clear" class="btn btn-primary btn-sm form-control" data-action="clear">Bersihkan</button>
                @if ($errors->has('ttd'))
                    <div class="invalid-feedback" style="display: block">{{$errors->first('ttd')}}</div>
                @endif
            </div>
        
        </div>

        <div class="kt-portlet__foot">
            <div class="kt-form__actions">
                <button name="save-as" type="button" class="btn btn-primary">Save As</button>
                <button type="button" class="btn btn-primary" onclick="get_time_now()">Start</button>
                <button type="submit" class="btn btn-primary" id='submitBtn'>Simpan Laporan</button>
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