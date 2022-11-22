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
        <div class="kt-section__info black">Input PO</div>
        <div class="kt-section__content">{{$data->no_po}}</div>
    </div>
    <div class="form-group">
        <div class="kt-section__info black">Tanggal Rencana Bunker</div>
        <div class="kt-section__content">{{Carbon\Carbon::parse($data->tanggal_rencana_bunker)->format('d-m-Y')}}</div>
    </div>
    
    <div class="form-group">
        <div class="kt-section__info black">Nama OOB</div>
        <div class="kt-section__content">{{$data->nama_oob}}</div>
    </div>
    <div class="form-group">
        <div class="kt-section__info black">Kapal</div>
        <div class="kt-section__content">{{$data->office}}</div>
    </div>
    <div class="form-group">
        <div class="kt-section__info black">Nomor RFB</div>
        <div class="kt-section__content">{{$data->nomor_rfb}}</div>
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