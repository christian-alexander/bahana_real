@extends('layouts.app')
@section('page-title')
<div class="row bg-title">
    <!-- .page title -->
    <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
        <h4 class="page-title"><i class="{{ $pageIcon }}"></i> {{ __($pageTitle) }} 
            @if ($spk->status=='done')
                {{-- <a href="{{route('admin.spk.cetak',$spk->id)}}" class="btn tbn-sm btn-success" target="_blank">Cetak</a> --}}
            @endif
        </h4>
    </div>
    <!-- /.page title -->
    <!-- .breadcrumb -->
    <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
        <ol class="breadcrumb">
            <li><a href="{{ route('member.dashboard') }}">@lang('app.menu.home')</a></li>
            <li><a href="{{ route('member.abk.index') }}">{{ __($pageTitle) }}</a></li>
            <li class="active">@lang('app.edit')</li>
        </ol>
    </div>
    <!-- /.breadcrumb -->
</div>
@endsection

@section('content')

<div class="row">
    <div class="col-md-12">

        <div class="panel panel-inverse">
            <div class="panel-wrapper collapse in" aria-expanded="true">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>User</label><br>
                                <span>{{$spk->user_id}}</span>
                            </div>
                            <div class="form-group">
                                <label>No PP</label><br>
                                <span>{{empty($spk->pp_id)?'-':$spk->pp_id}}</span>
                            </div>
                            <div class="form-group">
                                <label>Type</label><br>
                                <span>{{ strtoupper($spk->mt_or_spob)}}</span>
                            </div>
                            <div class="form-group">
                                <label>No</label><br>
                                <span>{{$spk->no}}</span>
                            </div>
                            <div class="form-group">
                                <label>Keperluan</label><br>
                                <span>{{$spk->keperluan}}</span>
                            </div>
                            <div class="form-group">
                                <label>Tanggal</label><br>
                                <span>{{Carbon\Carbon::parse($spk->tanggal)->format('d-m-Y')}}</span>
                            </div>
                            <div class="form-group">
                                <label>Status</label><br>
                                <span>{{strtoupper($spk->status)}}</span>
                            </div>
                            <div class="form-group">
                                <label>Status</label><br>
                                <span>{{$spk->status_approval}}</span>
                            </div>
                            <div class="form-group">
                                <label>Telah verifikasi SPV</label><br>
                                <span>{{$spk->verif_spv==0?'Belum':'Sudah'}}</span>
                            </div>
                            <div class="form-group">
                                <label>Catatan</label><br>
                                <span>{{$spk->note}}</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h3>List Barang</h3>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Barang</th>
                                        <th>Qty Diminta</th>
                                        <th>Qty Diestujui</th>
                                        <th>Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (count($spk->details)>0)
                                    @foreach ($spk->details as $item)
                                        <tr>
                                            <th>{{$loop->index+1}}</th>
                                            <td>{{$item->barang_etc}} {{!empty($item->deleted_at)?'(DELETED)':''}}</td>
                                            <td>{{$item->barang_diminta}}</td>
                                            <td>{{$item->barang_disetujui}}</td>
                                            <td>{{$item->ket}}</td>
                                        </tr>
                                    @endforeach
                                    @else
                                        <tr>
                                            <td colspan="5"><center>Tidak ada data</center></td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                            <h3>History Surat Permintaan Kapal</h3>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Aktivitas</th>
                                        <th>Tanggal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (count($spk->activity)>0)
                                        @foreach ($spk->activity as $item)
                                            <tr>
                                                <th>{{$loop->index+1}}</th>
                                                <td>{{$item->activity}}</td>
                                                <td>{{Carbon\Carbon::parse($item->created_at)->format('d-m-Y H:i:s')}}</td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="5" style="text-align: center">Tidak ada data</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <hr>
                    <div class="row" style="text-align: center">
                        <div class="col-md-4">
                            Pengaju<br><br>
                            <img src="{{asset($spk->signature_applicant)}}" alt="" width="150px"><br>
                            Nama: {{$spk->user->name}}
                        </div>
                        @if (count($spk->approval)>0)
                            @foreach ($spk->approval as $item)
                                @if (!empty($item->signature))
                                    <div class="col-md-4">
                                        {{$item->status=='approved_1'?'Nahkoda':'Manager'}}<br><br>
                                        <img src="{{asset($item->signature)}}" alt="" width="150px"><br>
                                        Nama: {{$item->approved_by_obj->name}} 
                                    </div>
                                @else
                                    <div class="col-md-4">
                                        {{$item->status=='approved_1'?'Nahkoda':'Manager'}}<br><br>
                                        -
                                        Nama: -
                                    </div>
                                @endif
                            @endforeach
                        @else
                            <div class="col-md-4">
                                Nahkoda<br><br>
                                -<br><br>
                                Nama: -
                            </div>
                            <div class="col-md-4">
                                Manager<br><br>
                                -<br><br>
                                Nama: -
                            </div>
                        @endif
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<!-- .row -->

@endsection
