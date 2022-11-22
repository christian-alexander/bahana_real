@extends('iframe.layouts.index')

@section('title')
    Sounding Pagi Perwir
@endsection

@section('body')
<style>
    table {
        table-layout: fixed;
        word-wrap: break-word;
    }
    span.select2.select2-container.select2-container{
        width: 220px !important;
    }
</style>
<form class="kt-form" id="form-store" method="POST" action="{{route('input-po.store',$user_id)}}" enctype="multipart/form-data">
    @csrf
    <div class="kt-portlet__body">
        <div class="form-group">
            <label for="exampleSelect1">Wilayah</label>
            <input type="text" name="wilayah" class="form-control">
            @if ($errors->has('wilayah'))
                <div class="invalid-feedback" style="display: block">{{$errors->first('wilayah')}}</div>
            @endif
        </div>
        <div class="form-group">
            <label for="exampleSelect1">Jenis Kegiatan</label>
            <select class="form-control" id="exampleSelect1" name="jenis_kegiatan">
                <option value="Jual/MBA">Jual/MBA</option>
                <option value="Ongkos Muat/OAT">Ongkos Muat/OAT</option>
            </select>
            @if ($errors->has('jenis_kegiatan'))
                <div class="invalid-feedback" style="display: block">{{$errors->first('jenis_kegiatan')}}</div>
            @endif
        </div>
        <div class="form-group row">
            <label for="example-date-input" class="col-2 col-form-label">Tanggal PO</label>
            <div class="col-10">
                <input class="form-control" type="date" name="tanggal_po" value="{{Carbon\Carbon::now()->format('Y-m-d')}}" id="example-date-input">
                @if ($errors->has('tanggal_po'))
                    <div class="invalid-feedback" style="display: block">{{$errors->first('tanggal_po')}}</div>
                @endif
            </div>
        </div>
        <div class="form-group">
            <label for="exampleSelect1">Perusahaan</label>
            <select class="form-control" id="exampleSelect1" name="perusahaan">
                @foreach ($perusahaan as $item)
                    <option value="{{$item->id}}">{{$item->name}}</option>
                @endforeach
            </select>
            @if ($errors->has('perusahaan'))
                <div class="invalid-feedback" style="display: block">{{$errors->first('perusahaan')}}</div>
            @endif
        </div>
        <div class="form-group">
            <label for="exampleSelect1">Nomor PO</label>
            <input type="text" name="nomor_po" class="form-control">
            @if ($errors->has('nomor_po'))
                <div class="invalid-feedback" style="display: block">{{$errors->first('nomor_po')}}</div>
            @endif
        </div>
        <div class="form-group">
            <label for="exampleSelect1">Customer</label>
            <input type="text" name="customer" class="form-control">
            @if ($errors->has('customer'))
                <div class="invalid-feedback" style="display: block">{{$errors->first('customer')}}</div>
            @endif
        </div>
        <div class="form-group">
            <label for="exampleSelect1">Kapal</label>
            <select class="form-control" id="exampleSelect1" name="kapal">
                @foreach ($kapal as $item)
                    <option value="{{$item->id}}">{{$item->name}}</option>
                @endforeach
            </select>
            @if ($errors->has('kapal'))
                <div class="invalid-feedback" style="display: block">{{$errors->first('kapal')}}</div>
            @endif
        </div>
        <div class="form-group">
            <label for="exampleSelect1">Contact Person</label>
            <input type="text" name="contact_person" class="form-control">
            @if ($errors->has('contact_person'))
                <div class="invalid-feedback" style="display: block">{{$errors->first('contact_person')}}</div>
            @endif
        </div>
        <div class="form-group">
            <label for="exampleSelect1">Jenis Produk</label>
            <select class="form-control" id="exampleSelect1" name="jenis_produk">
                <option value="LSFO">LSFO</option>
                <option value="B30">B30</option>
                <option value="HSFO">HSFO</option>
                <option value="MDF">MDF</option>
            </select>
            @if ($errors->has('jenis_produk'))
                <div class="invalid-feedback" style="display: block">{{$errors->first('jenis_produk')}}</div>
            @endif
        </div>
        <div class="form-group">
            <label for="exampleSelect1">Quantity</label>
            <input type="number" name="quantity" class="form-control">
            @if ($errors->has('quantity'))
                <div class="invalid-feedback" style="display: block">{{$errors->first('quantity')}}</div>
            @endif
        </div>
        <div class="form-group">
            <label for="exampleSelect1">Nomor SAO</label>
            <input type="text" name="nomor_sao" class="form-control">
            @if ($errors->has('nomor_sao'))
                <div class="invalid-feedback" style="display: block">{{$errors->first('nomor_sao')}}</div>
            @endif
        </div>
        <div class="form-group">
            <label for="exampleSelect1">File</label>
            <input type="file" name="file" class="form-control">
            @if ($errors->has('file'))
                <div class="invalid-feedback" style="display: block">{{$errors->first('file')}}</div>
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