@extends('iframe.layouts.index')

@section('title')
    Internal Memo
@endsection

@section('body')
<form class="kt-form" id="form-store" method="POST" action="{{route('permintaan-dana.store',$user_id)}}">
    @csrf
    <div class="kt-portlet__body">
        <div class="form-group">
            <label for="exampleSelect1">Anak Perusahaan</label>
            <select class="form-control" id="exampleSelect1" name="subcompany">
                @foreach ($subCompany as $item)
                    <option value="{{$item->id}}">{{$item->name}}</option>
                @endforeach
            </select>
            @if ($errors->has('subcompany'))
                <div class="invalid-feedback" style="display: block">{{$errors->first('subcompany')}}</div>
            @endif
        </div>
        
        <div class="form-group">
            <label for="exampleSelect1">Nama</label>
            <select class="form-control" id="exampleSelect1" name="user_id">
                @foreach ($data_user as $item)
                    <option value="{{$item->id}}">{{$item->name}}</option>
                @endforeach
            </select>
            @if ($errors->has('user_id'))
                <div class="invalid-feedback" style="display: block">{{$errors->first('user_id')}}</div>
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
            <label>Keperluan</label>
            <textarea class="form-control" name="keperluan" rows="3"></textarea>
            @if ($errors->has('keperluan'))
                <div class="invalid-feedback" style="display: block">{{$errors->first('keperluan')}}</div>
            @endif
        </div>
        <div class="form-group row">
            <label for="example-date-input" class="col-2 col-form-label">Nominal</label>
            <div class="col-10">
                <input class="form-control" type="number" name="nominal" id="example-date-input">
                @if ($errors->has('nominal'))
                    <div class="invalid-feedback" style="display: block">{{$errors->first('nominal')}}</div>
                @endif
            </div>
        </div>
        <div class="form-group">
            <label>Note</label>
            <textarea class="form-control" name="note" rows="3"></textarea>
            @if ($errors->has('note'))
                <div class="invalid-feedback" style="display: block">{{$errors->first('note')}}</div>
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