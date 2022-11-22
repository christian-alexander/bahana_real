@extends('layouts.app')
@push('head-script')
    <style>
        .d-none {
            display: none;
        }
        .btn.dropdown-toggle.selectpicker.btn-default{
            background: #ffffff !important;
        }
    </style>
@endpush
@section('page-title')
    <div class="row bg-title">
        <!-- .page title -->
        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
            <h4 class="page-title"><i class="{{ $pageIcon }}"></i> {{ __($pageTitle) }}</h4>
        </div>
        <!-- /.page title -->
        <!-- .breadcrumb -->
        <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
            <ol class="breadcrumb">
                <li><a href="{{ route('admin.dashboard') }}">@lang('app.menu.home')</a></li>
                <li><a href="{{ route('admin.notices.index') }}">{{ __($pageTitle) }}</a></li>
                <li class="active">@lang('app.addNew')</li>
            </ol>
        </div>
        <!-- /.breadcrumb -->
    </div>
@endsection

@section('content')

    <div class="row">
        <div class="col-md-12">

            <div class="panel panel-inverse">
                <div class="panel-heading"> @lang('modules.notices.addNotice')</div>
                <div class="panel-wrapper collapse in" aria-expanded="true">
                    <div class="panel-body">
                        {!! Form::open(['id'=>'createNotice','class'=>'ajax-form','method'=>'POST']) !!}
                            <div class="form-body">
                                <div class="row">
                                    <div class="col-xs-12 ">
                                        <div class="form-group">
                                            <label>@lang("modules.notices.noticeHeading")</label>
                                            <input type="text" name="heading" id="heading" class="form-control">
                                        </div>
                                    </div>

                                    {{-- <div class="col-xs-12">
                                        <div class="form-group">
                                            <div class="radio-list">
                                                <label class="radio-inline p-0">
                                                    <div class="radio radio-info">
                                                        <input type="radio" name="to" id="toEmployee" checked="" value="employee">
                                                        <label for="duration_single">@lang('modules.notices.toEmployee')</label>
                                                    </div>
                                                </label>
                                                <label class="radio-inline" style="display: none">
                                                    <div class="radio radio-info">
                                                        <input type="radio" name="to" id="toClient" value="client">
                                                        <label for="duration_multiple">@lang('modules.notices.toClients')</label>
                                                    </div>
                                                </label>

                                            </div>

                                        </div>
                                    </div> --}}
                                    <div class="col-xs-12 " id="sub_company">
                                        <div class="form-group">
                                            
                                            <label>Company</label>
                                            <select id="sub_company_id" name="sub_company_id" class="selectpicker form-control type">
                                                @foreach($sub_company as $company)
                                                    <option value="{{ $company->id }}">{{ ucwords($company->name) }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-xs-12">
                                        <div class="form-group">
                                            <label>All Department</label> &nbsp;&nbsp;
                                            <input type="checkbox" name="all"><br>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 " id="department">
                                        <div class="form-group">
                                            
                                            <label>@lang("app.department")</label>
                                            <select id="team_id" name="team_id[]"  multiple="multiple" class="selectpicker form-control type">
                                                @foreach($teams as $team)
                                                    <option value="{{ $team->id }}">{{ ucwords($team->team_name) }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <!--/row-->

                                <div class="row">
                                    <div class="col-xs-12">
                                        <div class="form-group">
                                            <label class="control-label">@lang("modules.notices.noticeDetails")</label>
                                            <textarea name="description" id="description" rows="5" class="form-control"></textarea>
                                        </div>
                                    </div>

                                </div>
                                <!--/span-->

                            </div>
                            <div class="form-actions">
                                <button type="submit" id="save-form" class="btn btn-success"> <i class="fa fa-check"></i> @lang('app.save')</button>

                            </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>    <!-- .row -->

@endsection

@push('footer-script')
<script>
    $(function () {
    // init
    var checkCheked = $('input[name=all]').is(':checked');
    if (checkCheked) {
        $('#department').hide();
    }else{
        $('#department').show();
    }
        $('.radio-list').click(function () {
            if($('input[name=to]:checked').val() === 'employee') {
                $('#department').removeClass('d-none').addClass('d-block');
            } else {
                $('#department').removeClass('d-block').addClass('d-none');
            }
        })

    });
    $('#save-form').click(function () {
        $.easyAjax({
            url: '{{route('admin.notices.store')}}',
            container: '#createNotice',
            type: "POST",
            redirect: true,
            data: $('#createNotice').serialize()
        })
    });
    $('input[name=all]').on('change', function(){
        if(this.checked) {
            $('#department').hide();
        }else{
            $('#department').show();
        }
    })
    
</script>

@endpush

