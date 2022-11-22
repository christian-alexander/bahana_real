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
                                    <label for="company_name">@lang('app.menu.office')</label>
                                    <input type="text" class="form-control" id="office_name" name="office_name">
                                </div>
                                <div class="form-group">
                                    <label for="company_name">Latitude</label>
                                    <input type="text" class="form-control" id="latitude" name="latitude">
                                </div>
                                <div class="form-group">
                                    <label for="company_name">Longitude</label>
                                    <input type="text" class="form-control" id="longitude" name="longitude">
                                </div>
                                <div class="form-group">
                                    <label for="radius">Radius</label>
                                    <input type="text" class="form-control" id="radius" name="radius">
                                </div>
                                <div class="form-group">
                                    <div class="input-group bootstrap-timepicker timepicker">
                                        <label>@lang('modules.attendance.jam_istirahat_awal')</label>
                                        <input type="text" name="jam_istirahat_awal" id="jam_istirahat_awal"
                                               class="form-control">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="input-group bootstrap-timepicker timepicker">
                                        <label>@lang('modules.attendance.jam_istirahat_akhir')</label>
                                        <input type="text" name="jam_istirahat_akhir" id="jam_istirahat_akhir"
                                               class="form-control">
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
        $('#jam_istirahat_awal, #jam_istirahat_akhir').timepicker({
            @if($global->time_format == 'H:i')
            showMeridian: false
            @endif
        });
        $('#save-form').click(function () {
            $.easyAjax({
                url: '{{route('admin.office.store')}}',
                container: '#createOffice',
                type: "POST",
                redirect: true,
                data: $('#createOffice').serialize()
            })
        });
    </script>
@endpush

