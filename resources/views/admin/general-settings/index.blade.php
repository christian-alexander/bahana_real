@extends('layouts.app')

@section('page-title')
    <div class="row bg-title">
        <!-- .page title -->
        <div class="col-lg-6 col-md-4 col-sm-4 col-xs-12">
            <h4 class="page-title"><i class="{{ $pageIcon }}"></i> {{ __($pageTitle) }}</h4>
        </div>
        <!-- /.page title -->
        <!-- .breadcrumb -->
        <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
            <ol class="breadcrumb">
                <li><a href="{{ route('admin.dashboard') }}">@lang('app.menu.home')</a></li>
                <li class="active">{{ __($pageTitle) }}</li>
            </ol>
        </div>
        <!-- /.breadcrumb -->
    </div>
@endsection

@push('head-script')
<link rel="stylesheet" href="{{ asset('plugins/bower_components/bootstrap-select/bootstrap-select.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/bower_components/timepicker/bootstrap-timepicker.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/bower_components/custom-select/custom-select.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/bower_components/multiselect/css/multi-select.css') }}">
@endpush

@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-inverse">
                <div class="panel-heading">Update General Setting</div>

                <div class="vtabs customvtab m-t-10">

                    @include('sections.admin_setting_menu')

                    <div class="tab-content">
                        <div id="vhome3" class="tab-pane active">
                            {!! Form::open(['id'=>'editSettings','class'=>'ajax-form','method'=>'POST']) !!}
                            <div class="row">
                                <div class="form-body">
                                    <div class="col-md-12">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="application_version">Application Version</label>
                                                <input type="text" class="form-control" id="application_version" name="application_version" value="{{isset($setting->general_setting->application_version)?$setting->general_setting->application_version:''}}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="update_version">Update Version</label>
                                                <input type="text" class="form-control" id="update_version" name="update_version" value="{{isset($setting->general_setting->update_version)?$setting->general_setting->update_version:''}}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="radius_tracking">Radius Tracking</label>
                                                <input type="number" class="form-control" id="radius_tracking" name="radius_tracking" value="{{isset($setting->general_setting->radius_tracking)?$setting->general_setting->radius_tracking:''}}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="bypass_store_gps_cluster">Bypass Store GPS Cluster</label> &nbsp;
                                                <input type="checkbox" id="bypass_store_gps_cluster" name="bypass_store_gps_cluster" {{isset($setting->general_setting->bypass_store_gps_cluster)?'checked':''}}>
                                            </div>
                                        </div>
                                    </div>
                                    <h4>Laporan Kerusakan</h4>
                                    <div class="col-md-12">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="diperiksa">Diperiksa</label>
                                                <select class="select2 form-control" name="laporan_kerusakan_diperiksa" data-style="form-control">
                                                    @foreach ($dataUser as $key =>  $item)
                                                        <option value="{{$key}}" {{isset($laporanKerusakan->diperiksa) &&!empty($laporanKerusakan->diperiksa)?($laporanKerusakan->diperiksa==$key?'selected':''):''}}>{{$item}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="mengetahui_1">Mengetahui 1</label>
                                                <select class="select2 form-control" name="laporan_kerusakan_mengetahui_1" data-style="form-control">
                                                    @foreach ($dataUser as $key =>  $item)
                                                        <option value="{{$key}}" {{isset($laporanKerusakan->mengetahui_1) &&!empty($laporanKerusakan->mengetahui_1)?($laporanKerusakan->mengetahui_1==$key?'selected':''):''}}>{{$item}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="mengetahui_2">Mengetahui 2</label>
                                                <select class="select2 form-control" name="laporan_kerusakan_mengetahui_2" data-style="form-control">
                                                    @foreach ($dataUser as $key =>  $item)
                                                        <option value="{{$key}}" {{isset($laporanKerusakan->mengetahui_2) &&!empty($laporanKerusakan->mengetahui_2)?($laporanKerusakan->mengetahui_2==$key?'selected':''):''}}>{{$item}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <h4>Laporan Penangguhan Pekerjaan</h4>
                                    <div class="col-md-12">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="diperiksa">Diperiksa</label>
                                                <select class="select2 form-control" name="laporan_penangguhan_pekerjaan_diperiksa" data-style="form-control">
                                                    @foreach ($dataUser as $key =>  $item)
                                                        <option value="{{$key}}" {{isset($laporanPenangguhanPekerjaan->diperiksa) &&!empty($laporanPenangguhanPekerjaan->diperiksa)?($laporanPenangguhanPekerjaan->diperiksa==$key?'selected':''):''}}>{{$item}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="mengetahui_1">Mengetahui 1</label>
                                                <select class="select2 form-control" name="laporan_penangguhan_pekerjaan_mengetahui_1" data-style="form-control">
                                                    @foreach ($dataUser as $key =>  $item)
                                                        <option value="{{$key}}" {{isset($laporanPenangguhanPekerjaan->mengetahui_1) &&!empty($laporanPenangguhanPekerjaan->mengetahui_1)?($laporanPenangguhanPekerjaan->mengetahui_1==$key?'selected':''):''}}>{{$item}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="mengetahui_2">Mengetahui 2</label>
                                                <select class="select2 form-control" name="laporan_penangguhan_pekerjaan_mengetahui_2" data-style="form-control">
                                                    @foreach ($dataUser as $key =>  $item)
                                                        <option value="{{$key}}" {{isset($laporanPenangguhanPekerjaan->mengetahui_2) &&!empty($laporanPenangguhanPekerjaan->mengetahui_2)?($laporanPenangguhanPekerjaan->mengetahui_2==$key?'selected':''):''}}>{{$item}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <h4>Laporan Perbaikan Kerusakan</h4>
                                    <div class="col-md-12">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="diperiksa">Diperiksa</label>
                                                <select class="select2 form-control" name="laporan_perbaikan_kerusakan_diperiksa" data-style="form-control">
                                                    @foreach ($dataUser as $key =>  $item)
                                                        <option value="{{$key}}" {{isset($laporanPerbaikanKerusakan->diperiksa) &&!empty($laporanPerbaikanKerusakan->diperiksa)?($laporanPerbaikanKerusakan->diperiksa==$key?'selected':''):''}}>{{$item}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="mengetahui_1">Mengetahui 1</label>
                                                <select class="select2 form-control" name="laporan_perbaikan_kerusakan_mengetahui_1" data-style="form-control">
                                                    @foreach ($dataUser as $key =>  $item)
                                                        <option value="{{$key}}" {{isset($laporanPerbaikanKerusakan->mengetahui_1) &&!empty($laporanPerbaikanKerusakan->mengetahui_1)?($laporanPerbaikanKerusakan->mengetahui_1==$key?'selected':''):''}}>{{$item}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="mengetahui_2">Mengetahui 2</label>
                                                <select class="select2 form-control" name="laporan_perbaikan_kerusakan_mengetahui_2" data-style="form-control">
                                                    @foreach ($dataUser as $key =>  $item)
                                                        <option value="{{$key}}" {{isset($laporanPerbaikanKerusakan->mengetahui_2) &&!empty($laporanPerbaikanKerusakan->mengetahui_2)?($laporanPerbaikanKerusakan->mengetahui_2==$key?'selected':''):''}}>{{$item}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <h4>Internal Memo</h4>
                                    <div class="col-md-12">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="mengetahui_1">Mengetahui 1</label>
                                                <select class="select2 form-control" name="internal_memo_mengetahui_1" data-style="form-control">
                                                    @foreach ($dataUserWithAtasan as $key =>  $item)
                                                        <option value="{{$key}}" {{isset($internalMemo->mengetahui_1) &&!empty($internalMemo->mengetahui_1)?($internalMemo->mengetahui_1==$key?'selected':''):''}}>{{$item}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="mengetahui_2">Mengetahui 2</label>
                                                <select class="select2 form-control" name="internal_memo_mengetahui_2" data-style="form-control">
                                                    @foreach ($dataUserWithAtasan as $key =>  $item)
                                                        <option value="{{$key}}" {{isset($internalMemo->mengetahui_2) &&!empty($internalMemo->mengetahui_2)?($internalMemo->mengetahui_2==$key?'selected':''):''}}>{{$item}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <h4>Permintaan Dana</h4>
                                    <div class="col-md-12">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="diperiksa">Diperiksa</label>
                                                <select class="select2 form-control" name="permintaan_dana_diperiksa" data-style="form-control">
                                                    @foreach ($dataUser as $key =>  $item)
                                                        <option value="{{$key}}" {{isset($permintaanDana->diperiksa) &&!empty($permintaanDana->diperiksa)?($permintaanDana->diperiksa==$key?'selected':''):''}}>{{$item}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="mengetahui_1">Mengetahui 1</label>
                                                <select class="select2 form-control" name="permintaan_dana_mengetahui_1" data-style="form-control">
                                                    @foreach ($dataUser as $key =>  $item)
                                                        <option value="{{$key}}" {{isset($permintaanDana->mengetahui_1) &&!empty($permintaanDana->mengetahui_1)?($permintaanDana->mengetahui_1==$key?'selected':''):''}}>{{$item}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="disetujui">Disetujui</label>
                                                <select class="select2 form-control" name="permintaan_dana_disetujui" data-style="form-control">
                                                    @foreach ($dataUser as $key =>  $item)
                                                        <option value="{{$key}}" {{isset($permintaanDana->disetujui) &&!empty($permintaanDana->disetujui)?($permintaanDana->disetujui==$key?'selected':''):''}}>{{$item}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <h4>Sounding Bunker Pemakaian BBM</h4>
                                    <div class="col-md-12">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="menyaksikan">Menyaksikan</label>
                                                <select class="select2 form-control" name="sbpbbm_menyaksikan" data-style="form-control">
                                                    @foreach ($dataUser as $key =>  $item)
                                                        <option value="{{$key}}" {{isset($soundingBunkerPemakaianBbm->menyaksikan) &&!empty($soundingBunkerPemakaianBbm->menyaksikan)?($soundingBunkerPemakaianBbm->menyaksikan==$key?'selected':''):''}}>{{$item}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="mengetahui_1">Mengetahui 1</label>
                                                <select class="select2 form-control" name="sbpbbm_mengetahui_1" data-style="form-control">
                                                    @foreach ($dataUser as $key =>  $item)
                                                        <option value="{{$key}}" {{isset($soundingBunkerPemakaianBbm->mengetahui_1) &&!empty($soundingBunkerPemakaianBbm->mengetahui_1)?($soundingBunkerPemakaianBbm->mengetahui_1==$key?'selected':''):''}}>{{$item}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="diperiksa">Diperiksa</label>
                                                <select class="select2 form-control" name="sbpbbm_diperiksa" data-style="form-control">
                                                    @foreach ($dataUser as $key =>  $item)
                                                        <option value="{{$key}}" {{isset($soundingBunkerPemakaianBbm->diperiksa) &&!empty($soundingBunkerPemakaianBbm->diperiksa)?($soundingBunkerPemakaianBbm->diperiksa==$key?'selected':''):''}}>{{$item}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="penerima">Penerima</label>
                                                <select class="select2 form-control" name="sbpbbm_penerima" data-style="form-control">
                                                    @foreach ($dataUser as $key =>  $item)
                                                        <option value="{{$key}}" {{isset($soundingBunkerPemakaianBbm->penerima) &&!empty($soundingBunkerPemakaianBbm->penerima)?($soundingBunkerPemakaianBbm->penerima==$key?'selected':''):''}}>{{$item}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <h4>Sounding Pagi Perwira</h4>
                                    <div class="col-md-12">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="menyetujui">Menyetujui</label>
                                                <select class="select2 form-control" name="sounding_pagi_perwira_menyetujui" data-style="form-control">
                                                    @foreach ($dataUser as $key =>  $item)
                                                        <option value="{{$key}}" {{isset($soundingPagiPerwira->menyetujui) &&!empty($soundingPagiPerwira->menyetujui)?($soundingPagiPerwira->menyetujui==$key?'selected':''):''}}>{{$item}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-actions m-t-15">
                                            <button type="submit" id="save-form"
                                                    class="btn btn-success waves-effect waves-light m-r-10">
                                                @lang('app.update')
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {!! Form::close() !!}

                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>

            </div>
        </div>


    </div>
    <!-- .row -->

@endsection

@push('footer-script')
<script src="{{ asset('plugins/bower_components/custom-select/custom-select.min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/bootstrap-select/bootstrap-select.min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/multiselect/js/jquery.multi-select.js') }}"></script>
<script src="{{ asset('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/timepicker/bootstrap-timepicker.min.js') }}"></script>


<script>
    $(".select2").select2({
        formatNoMatches: function () {
            return "{{ __('messages.noRecordFound') }}";
        }
    });
    $('#office_end_time, #office_start_time, #halfday_mark_time').timepicker({
        @if($global->time_format == 'H:i')
        showMeridian: false
        @endif
    });

    $('#save-form').click(function () {
        $.easyAjax({
            url: '{{route('admin.general-settings.store')}}',
            container: '#editSettings',
            type: "POST",
            redirect: true,
            data: $('#editSettings').serialize()
        })
    });
    
</script>

@endpush

