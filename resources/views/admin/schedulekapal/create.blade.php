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
<link rel="stylesheet" href="{{ asset('plugins/bower_components/custom-select/custom-select.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/bower_components/bootstrap-select/bootstrap-select.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.css') }}">
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

                                <div class="col-md-6" id="single-date">
                                    <label>Tanggal awal</label>
                                    <div class="form-group">
                                        <input type="text" class="form-control" name="date_start" id="single_date" value="{{ Carbon\Carbon::today()->format($global->date_format) }}">
                                    </div>
                                </div>
                              
                                <div class="col-md-6" id="single-date">
                                    <label>Tanggal akhir</label>
                                    <div class="form-group">
                                        <input type="text" class="form-control" name="date_end" id="single_date2" value="{{ Carbon\Carbon::today()->format($global->date_format) }}">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>ABK Kapal
                                        {{-- <a href="javascript:;" id="cabang-setting" ><i class="ti-settings text-info"></i></a> --}}
                                    </label>
                                    <select name="user_id" id="user_id" class="form-control">
                                        <option value="">--</option>
                                        @forelse($listEmployee as $emp)
                                            <option value="{{ $emp->id }}">{{ $emp->name }}</option>
                                        @empty
                                            <option value="">@lang('messages.noRecordFound')</option>
                                        @endforelse()
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label>Kapal
                                        {{-- <a href="javascript:;" id="cabang-setting" ><i class="ti-settings text-info"></i></a> --}}
                                    </label>
                                    <select name="kapal_id" id="kapal_id" class="form-control">
                                        <option value="">--</option>
                                        @forelse($kapal as $kap)
                                            <option value="{{ $kap->id }}">{{ $kap->name }}</option>
                                        @empty
                                            <option value="">@lang('messages.noRecordFound')</option>
                                        @endforelse()
                                    </select>
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
<script src="{{ asset('plugins/bower_components/custom-select/custom-select.min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/bootstrap-select/bootstrap-select.min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
    <script>
        $('#jam_istirahat_awal, #jam_istirahat_akhir').timepicker({
            @if($global->time_format == 'H:i')
            showMeridian: false
            @endif
        });
        $('#save-form').click(function () {
            $.easyAjax({
                url: '{{route('admin.schedulekapal.store')}}',
                container: '#createOffice',
                type: "POST",
                redirect: true,
                data: $('#createOffice').serialize()
            })
        });
      
    jQuery('#single_date').datepicker({
        autoclose: true,
        todayHighlight: true,
        weekStart:'{{ $global->week_start }}',
        format: '{{ $global->date_picker_format }}',
    });
      
    jQuery('#single_date2').datepicker({
        autoclose: true,
        todayHighlight: true,
        weekStart:'{{ $global->week_start }}',
        format: '{{ $global->date_picker_format }}',
    });
    </script>
@endpush

