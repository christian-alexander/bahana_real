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
                <li><a href="{{ route('admin.subcompany.index') }}">{{ $pageTitle }}</a></li>
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
@endpush

@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-inverse">
                <div class="panel-heading">@lang('app.update') @lang('app.menu.subcompany')</div>
                <p class="text-muted font-13"></p>

                <div class="panel-wrapper collapse in" aria-expanded="true">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-sm-12 col-xs-12">
                                {!! Form::open(['id'=>'createCurrency','class'=>'ajax-form','method'=>'PUT']) !!}
                                <div class="form-group">
                                    <label for="code">Code</label>
                                    <input type="text" class="form-control" id="code" name="code" value="{{ $subcompany->code }}">
                                </div>
                                <div class="form-group">
                                    <label for="company_name">@lang('app.menu.subcompany')</label>
                                    <input type="text" class="form-control" id="subcompany_name" name="subcompany_name" value="{{ $subcompany->name }}">
                                </div>

                                <div class="form-group">
                                    <label for="company_name">HRD</label>
                                    <select name="hrd" id="hrd" class="form-control select2">
                                        <option value="">Default</option>
                                        @foreach ($users as $val)
                                            <option value="{{$val->id}}" {{$subcompany->hrd==$val->id?'selected':''}}>{{$val->name}}</option>
                                        @endforeach
                                    </select>
                                    <small>Biarkan kosong untuk mengikuti settingan default</small>
                                </div>

                                <button type="submit" id="save-form" class="btn btn-success waves-effect waves-light m-r-10">
                                    @lang('app.save')
                                </button>
                                {!! Form::close() !!}
                                <hr>
                            </div>


                            <div class="col-md-7">
                                <h3 class="box-title m-b-0">@lang('modules.projects.members')</h3>

                            @forelse($subcompany->members as $member)
                                    <div class="row">
                                        <div class="col-sm-2 col-md-1 p-10">
                                            {!!  '<img src="'.$member->user->image_url.'"
                                                            alt="user" class="img-circle" width="40" height="40">' !!}

                                        </div>
                                        <div class="col-sm-7">
                                            <h5>{{ ucwords($member->user->name) }}</h5>
                                            <h6>{{ $member->user->email }}</h6>
                                        </div>
                                    </div>
                                @empty
                                    @lang('messages.noRecordFound')
                                @endforelse
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
    <script>
        $(".select2").select2();

        $('#save-form').click(function () {
            $.easyAjax({
                url: '{{route('admin.subcompany.update', [$subcompany->id])}}',
                container: '#createCurrency',
                type: "POST",
                redirect: true,
                data: $('#createCurrency').serialize()
            })
        });
        $("#persetujuan_dua").select2({
            formatNoMatches: function () {
                return "{{ __('messages.noRecordFound') }}";
            }
        });

    </script>
@endpush

