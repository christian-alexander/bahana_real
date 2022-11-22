@extends('layouts.app')

@section('page-title')
    <div class="row bg-title">
        <!-- .page title -->
        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
            <h4 class="page-title"><i class="{{ $pageIcon }}"></i> {{ $pageTitle }}</h4>
        </div>
        <!-- /.page title -->
        <!-- .breadcrumb -->
        <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
            <ol class="breadcrumb">
                <li><a href="{{ route('admin.dashboard') }}">@lang('app.menu.home')</a></li>
                <li><a href="{{ route('admin.office.index') }}">{{ $pageTitle }}</a></li>
                <li class="active">@lang('app.addNew')</li>
            </ol>
        </div>
        <!-- /.breadcrumb -->
    </div>
@endsection

@push('head-script')
<link rel="stylesheet" href="{{ asset('plugins/bower_components/timepicker/bootstrap-timepicker.min.css') }}">
@endpush

@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-inverse">
                <div class="panel-heading">@lang('app.add') @lang('app.menu.office')</div>

                <p class="text-muted font-13"></p>

                <div class="panel-wrapper collapse in" aria-expanded="true">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-sm-12 col-xs-12">
                                {!! Form::open(['id'=>'createOffice','class'=>'ajax-form','method'=>'POST']) !!}
                                <div class="form-group">
                                    <label>@lang('modules.employees.cluster_name')</label>
                                    <input type="text" name="cluster_name" id="cluster_name" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label>Type</label>
                                    <select class="form-control" name="type" id="type">
                                        <option value="daily">Daily</option>
                                        <option value="shift">Shift</option>
                                    </select>
                                </div>
                                <div id="daily">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Hari</th>
                                                <th>Jam Masuk</th>
                                                <th>Jam Pulang</th>
                                                <th>Jam Istirahat Awal</th>
                                                <th>Jam Istirahat Akhir</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>Senin</td>
                                                <td>
                                                    <div class="form-group">
                                                        <div class="input-group form-control bootstrap-timepicker timepicker">
                                                            <input type="text" name="senin_jam_masuk" class="form-control type_time">
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group">
                                                        <div class="input-group form-control bootstrap-timepicker timepicker">
                                                            <input type="text" name="senin_jam_pulang" class="form-control type_time">
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group">
                                                        <div class="input-group form-control bootstrap-timepicker timepicker">
                                                            <input type="text" name="senin_istirahat_awal" class="form-control type_time">
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group">
                                                        <div class="input-group form-control bootstrap-timepicker timepicker">
                                                            <input type="text" name="senin_istirahat_akhir" class="form-control type_time">
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Selasa</td>
                                                <td>
                                                    <div class="form-group">
                                                        <div class="input-group form-control bootstrap-timepicker timepicker">
                                                            <input type="text" name="selasa_jam_masuk" class="form-control type_time">
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group">
                                                        <div class="input-group form-control bootstrap-timepicker timepicker">
                                                            <input type="text" name="selasa_jam_pulang" class="form-control type_time">
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group">
                                                        <div class="input-group form-control bootstrap-timepicker timepicker">
                                                            <input type="text" name="selasa_istirahat_awal" class="form-control type_time">
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group">
                                                        <div class="input-group form-control bootstrap-timepicker timepicker">
                                                            <input type="text" name="selasa_istirahat_akhir" class="form-control type_time">
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Rabu</td>
                                                <td>
                                                    <div class="form-group">
                                                        <div class="input-group form-control bootstrap-timepicker timepicker">
                                                            <input type="text" name="rabu_jam_masuk" class="form-control type_time">
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group">
                                                        <div class="input-group form-control bootstrap-timepicker timepicker">
                                                            <input type="text" name="rabu_jam_pulang" class="form-control type_time">
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group">
                                                        <div class="input-group form-control bootstrap-timepicker timepicker">
                                                            <input type="text" name="rabu_istirahat_awal" class="form-control type_time">
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group">
                                                        <div class="input-group form-control bootstrap-timepicker timepicker">
                                                            <input type="text" name="rabu_istirahat_akhir" class="form-control type_time">
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Kamis</td>
                                                <td>
                                                    <div class="form-group">
                                                        <div class="input-group form-control bootstrap-timepicker timepicker">
                                                            <input type="text" name="kamis_jam_masuk" class="form-control type_time">
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group">
                                                        <div class="input-group form-control bootstrap-timepicker timepicker">
                                                            <input type="text" name="kamis_jam_pulang" class="form-control type_time">
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group">
                                                        <div class="input-group form-control bootstrap-timepicker timepicker">
                                                            <input type="text" name="kamis_istirahat_awal" class="form-control type_time">
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group">
                                                        <div class="input-group form-control bootstrap-timepicker timepicker">
                                                            <input type="text" name="kamis_istirahat_akhir" class="form-control type_time">
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Jumat</td>
                                                <td>
                                                    <div class="form-group">
                                                        <div class="input-group form-control bootstrap-timepicker timepicker">
                                                            <input type="text" name="jumat_jam_masuk" class="form-control type_time">
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group">
                                                        <div class="input-group form-control bootstrap-timepicker timepicker">
                                                            <input type="text" name="jumat_jam_pulang" class="form-control type_time">
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group">
                                                        <div class="input-group form-control bootstrap-timepicker timepicker">
                                                            <input type="text" name="jumat_istirahat_awal" class="form-control type_time">
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group">
                                                        <div class="input-group form-control bootstrap-timepicker timepicker">
                                                            <input type="text" name="jumat_istirahat_akhir" class="form-control type_time">
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Sabtu</td>
                                                <td>
                                                    <div class="form-group">
                                                        <div class="input-group form-control bootstrap-timepicker timepicker">
                                                            <input type="text" name="sabtu_jam_masuk" class="form-control type_time">
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group">
                                                        <div class="input-group form-control bootstrap-timepicker timepicker">
                                                            <input type="text" name="sabtu_jam_pulang" class="form-control type_time">
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group">
                                                        <div class="input-group form-control bootstrap-timepicker timepicker">
                                                            <input type="text" name="sabtu_istirahat_awal" class="form-control type_time">
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group">
                                                        <div class="input-group form-control bootstrap-timepicker timepicker">
                                                            <input type="text" name="sabtu_istirahat_akhir" class="form-control type_time">
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Minggu</td>
                                                <td>
                                                    <div class="form-group">
                                                        <div class="input-group form-control bootstrap-timepicker timepicker">
                                                            <input type="text" name="minggu_jam_masuk" class="form-control type_time">
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group">
                                                        <div class="input-group form-control bootstrap-timepicker timepicker">
                                                            <input type="text" name="minggu_jam_pulang" class="form-control type_time">
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group">
                                                        <div class="input-group form-control bootstrap-timepicker timepicker">
                                                            <input type="text" name="minggu_istirahat_awal" class="form-control type_time">
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group">
                                                        <div class="input-group form-control bootstrap-timepicker timepicker">
                                                            <input type="text" name="minggu_istirahat_akhir" class="form-control type_time">
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div id="shift">
                                    <div class="form-group">
                                        <div class="input-group bootstrap-timepicker timepicker">
                                            <label>@lang('modules.employees.start_hour')</label>
                                            <input type="text" name="start_hour" id="start_hour"
                                                   class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="input-group bootstrap-timepicker timepicker">
                                            <label>@lang('modules.employees.end_hour')</label>
                                            <input type="text" name="end_hour" id="end_hour"
                                                   class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" id="save-form" class="btn btn-success waves-effect waves-light m-r-10">
                                    @lang('app.save')
                                </button>
                                <a href="{{route('admin.office.index')}}" class="btn btn-default waves-effect waves-light">@lang('app.back')</a>
                                {!! Form::close() !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- .row -->

@endsection

@push('footer-script')
    <script src="{{ asset('plugins/bower_components/timepicker/bootstrap-timepicker.min.js') }}"></script>
    <script>
        $('.type_time, #start_hour, #end_hour').timepicker({
            @if($global->time_format == 'H:i')
            showMeridian: false
            @endif
        });
        $('#save-form').click(function () {
            $.easyAjax({
                url: '{{route('admin.cluster-working-hour.store')}}',
                container: '#createOffice',
                type: "POST",
                redirect: true,
                data: $('#createOffice').serialize()
            })
        });
        var init_type = $('#type').val();
        if (init_type=='daily') {
            $('#daily').show();
            $('#shift').hide();
        }else{
            $('#daily').hide();
            $('#shift').show();
        }
        $('#type').on('change', function(){
            var val = $(this).val();
            if (val=='daily') {
                $('#daily').show();
                $('#shift').hide();
            }else{
                $('#daily').hide();
                $('#shift').show();
            }
        });
    </script>
@endpush

