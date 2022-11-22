@extends('iframe.layouts.index')

@section('title')
    Internal Memo
@endsection

@section('body')
<form class="kt-form" id="form-store" method="POST" action="{{route('internal-memo.store',$user_id)}}">
    @csrf
    <div class="kt-portlet__body">
        <div class="form-group">
            <label for="exampleSelect1">Anak Perusahaan</label>
            <select class="form-control" id="exampleSelect1" name="anak_perusahaan">
                @foreach ($subCompany as $item)
                    <option value="{{$item->id}}">{{$item->name}}</option>
                @endforeach
            </select>
            @if ($errors->has('anak_perusahaan'))
                <div class="invalid-feedback" style="display: block">{{$errors->first('anak_perusahaan')}}</div>
            @endif
        </div>
        <div class="form-group">
            <label for="exampleSelect1">Department</label>
            <select class="form-control" id="exampleSelect1" name="department">
                @foreach ($department as $item)
                    <option value="{{$item->id}}">{{$item->team_name}}</option>
                @endforeach
            </select>
            @if ($errors->has('department'))
                <div class="invalid-feedback" style="display: block">{{$errors->first('department')}}</div>
            @endif
        </div>
        <div class="form-group">
            <label for="exampleSelect1">Wilayah</label>
            <select class="form-control" id="exampleSelect1" name="wilayah">
                @foreach ($wilayah as $item)
                    <option value="{{$item->id}}">{{$item->name}}</option>
                @endforeach
            </select>
            @if ($errors->has('wilayah'))
                <div class="invalid-feedback" style="display: block">{{$errors->first('wilayah')}}</div>
            @endif
        </div>
        <div class="form-group">
            <label for="exampleSelect1">Dari</label>
            <select class="form-control" id="exampleSelect1" name="dari">
                @foreach ($data_user as $item)
                    <option value="{{$item->id}}">{{$item->name}}</option>
                @endforeach
            </select>
            @if ($errors->has('dari'))
                <div class="invalid-feedback" style="display: block">{{$errors->first('dari')}}</div>
            @endif
        </div>
        <div class="form-group">
            <label for="exampleSelect1">Kepada</label>
            <select class="form-control" id="exampleSelect1" name="kepada">
                @foreach ($data_user as $item)
                    <option value="{{$item->id}}">{{$item->name}}</option>
                @endforeach
            </select>
            @if ($errors->has('kepada'))
                <div class="invalid-feedback" style="display: block">{{$errors->first('kepada')}}</div>
            @endif
        </div>
        <div class="form-group row">
            <label for="example-date-input" class="col-2 col-form-label">Tanggal</label>
            <div class="col-10">
                <input class="form-control" type="date" name="tanggal" value="{{Carbon\Carbon::now()->format('Y-m-d')}}" id="example-date-input">
                @if ($errors->has('tanggal'))
                    <div class="invalid-feedback" style="display: block">{{$errors->first('tanggal')}}</div>
                @endif
            </div>
        </div>
        <div class="form-group">
            <label for="exampleSelect1">Tempat</label>
            <input type="text" name="tempat" class="form-control">
            @if ($errors->has('tempat'))
                <div class="invalid-feedback" style="display: block">{{$errors->first('tempat')}}</div>
            @endif
        </div>
        <div class="form-group">
            <label for="exampleSelect1">Perihal</label>
            <input type="text" name="perihal" class="form-control">
            @if ($errors->has('perihal'))
                <div class="invalid-feedback" style="display: block">{{$errors->first('perihal')}}</div>
            @endif
        </div>
        <div class="form-group">
            <label for="exampleSelect1">Sifat</label>
            <select class="form-control" id="exampleSelect1" name="sifat">
                <option value="biasa">Biasa</option>
                <option value="penting">Penting</option>
                <option value="penting-sekali">Penting Sekali</option>
            </select>
            @if ($errors->has('sifat'))
                <div class="invalid-feedback" style="display: block">{{$errors->first('sifat')}}</div>
            @endif
        </div>
        <div class="form-group">
            <label>Berita</label>
            <textarea class="form-control" name="berita" rows="3"></textarea>
            @if ($errors->has('berita'))
                <div class="invalid-feedback" style="display: block">{{$errors->first('berita')}}</div>
            @endif
        </div>
        <div class="form-group">
            <label for="exampleSelect1">CC ke</label>
            <select class="form-control my-select2" name="cc[]" multiple="multiple">
                @foreach ($data_user as $item)
                    <option value="{{$item->id}}">{{$item->name}}</option>
                @endforeach
            </select>
        </div>
        <label>Tanda tangan</label>
        <div id="signature-pad" class="signature-pad" style="height: 250px;border: none;box-shadow: none;padding: 0">
            <div class="signature-pad--body">
                <canvas style="width: 100%;height: 100%;border: solid 1px #ccc;"></canvas>
            </div>
            <textarea id="signature64" name="tanda_tangan" style="display: none"></textarea>
            <button type="button" id="clear" class="btn btn-primary btn-sm form-control" data-action="clear">Bersihkan</button>
            @if ($errors->has('tanda_tangan'))
                <div class="invalid-feedback" style="display: block">{{$errors->first('tanda_tangan')}}</div>
            @endif
        </div>
    </div>
    <div class="kt-portlet__foot">
        <div class="kt-form__actions">
            <button type="submit" class="btn btn-primary" id="submitBtn">Submit</button>
            <button type="reset" class="btn btn-secondary">Cancel</button>
        </div>
    </div>
</form>
@endsection
@section('script')
    <script>
        $(document).ready(function(){
            // code
        })
    </script>
@endsection