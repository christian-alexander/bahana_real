@extends('iframe.layouts.index')

@section('title')
    Detail Internal Memo
@endsection

@section('body')
<style>
    .black{
        color: black
    }
</style>
<form class="kt-form" method="POST" action="{{route('sounding-pagi-perwira.approve',[$user->id, $data->id])}}">
@csrf
<div class="kt-portlet__body">
    <div class="form-group">
        <div class="kt-section__info black">Wilayah</div>
        <div class="kt-section__content">{{$data->wilayah}}</div>
    </div>
    <div class="form-group">
        <div class="kt-section__info black">Jenis Kegiatan</div>
        <div class="kt-section__content">{{$data->jenis_kegiatan}}</div>
    </div>
    <div class="form-group">
        <div class="kt-section__info black">Tanggal PO</div>
        <div class="kt-section__content">{{Carbon\Carbon::parse($data->tanggal_po)->format('d-m-Y')}}</div>
    </div>
    <div class="form-group">
        <div class="kt-section__info black">Perusahaan</div>
        <div class="kt-section__content">{{$data->perusahaan}}</div>
    </div>
    <div class="form-group">
        <div class="kt-section__info black">Nomor PO</div>
        <div class="kt-section__content">{{$data->no_po}}</div>
    </div>
    
    <div class="form-group">
        <div class="kt-section__info black">Customer</div>
        <div class="kt-section__content">{{$data->nama_customer}}</div>
    </div>
    <div class="form-group">
        <div class="kt-section__info black">Kapal</div>
        <div class="kt-section__content">{{$data->office}}</div>
    </div>
    <div class="form-group">
        <div class="kt-section__info black">Contact Person</div>
        <div class="kt-section__content">{{$data->contact_person}}</div>
    </div>
    <div class="form-group">
        <div class="kt-section__info black">Jenis Produk</div>
        <div class="kt-section__content">{{$data->jenis_produk}}</div>
    </div>
    <div class="form-group">
        <div class="kt-section__info black">Quantity</div>
        <div class="kt-section__content">{{$data->qty}}</div>
    </div>
    <div class="form-group">
        <div class="kt-section__info black">Nomor SAO</div>
        <div class="kt-section__content">{{$data->no_sao}}</div>
    </div>
    <div class="form-group">
        <div class="kt-section__info black">File</div>
        <div class="kt-section__content">{{url($data->image)}}</div>
    </div>
</div>

@endsection
@section('script')
    <script>
        $(document).ready(function(){
            //code
        })
    </script>
@endsection