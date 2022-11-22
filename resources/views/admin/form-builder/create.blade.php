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
                <li><a href="{{ route('admin.pertanyaan.index') }}">{{ $pageTitle }}</a></li>
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
                <div class="panel-heading">@lang('app.add') @lang('app.menu.pertanyaan')</div>

                <p class="text-muted font-13"></p>

                <div class="panel-wrapper collapse in" aria-expanded="true">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-sm-12 col-xs-12">
                                {!! Form::open(['id'=>'createPertanyaan','class'=>'ajax-form','method'=>'POST']) !!}
                                <div class="form-group">
                                    <label for="company_name">Nama Form</label>
                                    <input type="text" class="form-control" name="nama_form" id="nama_form" placeholder="Nama Form">
                                </div>
                                <div class="form-group">
                                    <label for="company_name">Atribut</label>
                                    <div id="build-wrap"></div>
                                </div>
                                <br>
                                <button type="submit" id="save-form" class="btn btn-success waves-effect waves-light m-r-10">
                                    @lang('app.save')
                                </button>
                                <a href="{{route('admin.form-builder.index')}}" class="btn btn-default waves-effect waves-light">@lang('app.back')</a>
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
    <script src="{{ asset('public/vendor/form-builder/form-builder.min.js') }}"></script>
    <script>
        $('#jam_istirahat_awal, #jam_istirahat_akhir').timepicker({
            @if($global->time_format == 'H:i')
            showMeridian: false
            @endif
        });
        var options = {
            disableFields: ['autocomplete','paragraph','header','hidden','number','button','file','checkbox-group','radio-group','date'],
            showActionButtons: false,
            typeUserEvents: {
                text: {
                    onadd: function(fld) {
                        $(fld).find(".form-group.description-wrap label").text('Mapping with table');
                        $(fld).find(".form-group.placeholder-wrap label").text('Mapping with field');
                        // console.log(selector);
                    }
                },
                textarea: {
                    onadd: function(fld) {
                        $(fld).find(".form-group.description-wrap label").text('Mapping with table');
                        $(fld).find(".form-group.placeholder-wrap label").text('Mapping with field');
                    }
                },
                'checkbox-group': {
                    onadd: function(fld) {
                        console.log('asd1');
                        $(fld).find(".form-group.description-wrap label").text('Mapping with table');
                        $(fld).find(".form-group.className-wrap label").text('Mapping with field');
                        $(fld).find(".form-group.className-wrap input").attr('placeholder','Mapping with field');
                        // console.log(selector);
                    }
                },
                date: {
                    onadd: function(fld) {
                        $(fld).find(".form-group.description-wrap label").text('Mapping with table');
                        $(fld).find(".form-group.placeholder-wrap label").text('Mapping with field');
                    }
                },
                'radio-group': {
                    onadd: function(fld) {
                        console.log('asd1');
                        $(fld).find(".form-group.description-wrap label").text('Mapping with table');
                        $(fld).find(".form-group.className-wrap label").text('Mapping with field');
                        $(fld).find(".form-group.className-wrap input").attr('placeholder','Mapping with field');
                        // console.log(selector);
                    }
                },
                select: {
                    onadd: function(fld) {
                        $(fld).find(".form-group.description-wrap label").text('Mapping with table');
                        $(fld).find(".form-group.placeholder-wrap label").text('Mapping with field');
                        $(fld).find(".form-group.className-wrap label").text('Source,value,label(,)');
                        $(fld).find(".form-group.className-wrap input").val('');
                    }
                },
            },
        };
        var fbEditor = document.getElementById('build-wrap');
        var formBuilder = $(fbEditor).formBuilder(options);
        $('#save-form').click(function () {
            $.easyAjax({
                url: '{{route('admin.form-builder.store')}}',
                container: '#createPertanyaan',
                type: "POST",
                redirect: true,
                data: {
                    "_token": "{{ csrf_token() }}",
                    "nama_form":$("input[name=nama_form]").val(),
                    "atribut":formBuilder.actions.getData('json')
                }
            })
        });
    </script>
@endpush

