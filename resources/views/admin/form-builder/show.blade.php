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
    <link rel="stylesheet" href="{{ asset('plugins/bower_components/icheck/skins/all.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/bower_components/custom-select/custom-select.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/bower_components/bootstrap-select/bootstrap-select.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/bower_components/multiselect/css/multi-select.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/bower_components/timepicker/bootstrap-timepicker.min.css') }}">
@endpush

@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-inverse">
                <div class="panel-heading">Detail Form</div>
                <p class="text-muted font-13"></p>

                <div class="panel-wrapper collapse in" aria-expanded="true">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-sm-12 col-xs-12">
                                {{-- <div class="form-group">
                                    <label for="company_name">@lang('app.menu.pertanyaan')</label>
                                    <textarea class="form-control" name="pertanyaan" id="pertanyaan" cols="30" rows="10"></textarea>
                                </div> --}}
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover toggle-circle default footable-loaded footable" id="users-table">
                                        <thead>
                                        <tr>
                                            <th>@lang('app.id')</th>
                                            <th>Label</th>
                                            <th>Field Name</th>
                                            <th>Field Type</th>
                                            <th>Dropdown Table Name</th>
                                            <th>Dropdown Table Value</th>
                                            <th>Dropdown Table Label</th>
                                            <th>Dropdown Option</th>
                                            <th>Field Default Value</th>
                                            <th>Nullable</th>
                                            <th>Primary Key</th>
                                            <th>Reference Table Name</th>
                                            <th>Reference Field Name</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($form_field as $item)
                                            <tr id="group{{ $item->id }}">
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $item->label }}</td>
                                                <td>{{ $item->field_name }}</td>
                                                <td>{{ $item->field_type }}</td>
                                                <td>{{ $item->dropdown_table_name }}</td>
                                                <td>{{ $item->dropdown_table_value }}</td>
                                                <td>{{ $item->dropdown_table_label }}</td>
                                                <td>{{ $item->dropdown_option }}</td>
                                                <td>{{ $item->field_default_value }}</td>
                                                <td>{{ $item->nullable==1?'Yes':'No' }}</td>
                                                <td>{{ $item->pk==1?'Yes':'No' }}</td>
                                                <td>{{ $item->reference_table_name }}</td>
                                                <td>{{ $item->reference_field_name }}</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <hr>
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
    <script src="{{ asset('js/cbpFWTabs.js') }}"></script>
    <script src="{{ asset('plugins/bower_components/custom-select/custom-select.min.js') }}"></script>
    <script src="{{ asset('plugins/bower_components/bootstrap-select/bootstrap-select.min.js') }}"></script>
    <script src="{{ asset('plugins/bower_components/multiselect/js/jquery.multi-select.js') }}"></script>
    <script src="{{ asset('plugins/bower_components/timepicker/bootstrap-timepicker.min.js') }}"></script>
    <script>
        $(".select2").select2();
        $('#jam_istirahat_awal, #jam_istirahat_akhir').timepicker({
            @if($global->time_format == 'H:i')
            showMeridian: false
            @endif
        });
    </script>
@endpush

