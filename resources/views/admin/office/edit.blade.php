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
                <div class="panel-heading">@lang('app.update') @lang('app.menu.office')</div>
                <p class="text-muted font-13"></p>

                <div class="panel-wrapper collapse in" aria-expanded="true">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-sm-12 col-xs-12">
                                {!! Form::open(['id'=>'createCurrency','class'=>'ajax-form','method'=>'PUT']) !!}

                                <div class="form-group">
                                    <label for="company_name">Code</label>
                                    <input type="text" class="form-control" value="{{ $office->code }}" readonly>
                                </div>
                                <div class="form-group">
                                    <label for="company_name">@lang('app.menu.office')</label>
                                    <input type="text" class="form-control" id="office_name" name="office_name" value="{{ $office->name }}">
                                </div>
                                <div class="form-group">
                                    <label for="company_name">Latitude</label>
                                    <input type="text" class="form-control" id="latitude" name="latitude" value="{{ $office->latitude }}">
                                </div>
                                <div class="form-group">
                                    <label for="company_name">Longitude</label>
                                    <input type="text" class="form-control" id="longitude" name="longitude" value="{{ $office->longitude }}">
                                </div>
                                <div class="form-group">
                                    <label for="radius">Radius</label>
                                    <input type="text" class="form-control" id="radius" name="radius" value="{{ $office->radius }}">
                                </div>
                                <div class="form-group">
                                    <div class="input-group bootstrap-timepicker timepicker">
                                        <label>@lang('modules.attendance.jam_istirahat_awal')</label>
                                        <input type="text" name="jam_istirahat_awal" id="jam_istirahat_awal" class="form-control" value="{{ $office->jam_istirahat_awal }}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="input-group bootstrap-timepicker timepicker">
                                        <label>@lang('modules.attendance.jam_istirahat_akhir')</label>
                                        <input type="text" name="jam_istirahat_akhir" id="jam_istirahat_akhir" class="form-control" value="{{ $office->jam_istirahat_akhir }}">
                                    </div>
                                </div>

                                <button type="submit" id="save-form" class="btn btn-success waves-effect waves-light m-r-10">
                                    @lang('app.save')
                                </button>
                                <a href="#" class="btn btn-info waves-effect waves-light m-r-10" style="color:#ffffff" id="add-bssid">Tambah BSSID</a>
                                {!! Form::close() !!}
                                <hr>
                            </div>

                        </div>
                        
                        <table class="table table-bordered table-hover toggle-circle default footable-loaded footable" id="users-table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Name</th>
                                    <th>BSSID</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (count($getWifi)>0)
                                    @foreach ($getWifi as $item)
                                    <tr>
                                        <td>{{$loop->iteration}}</td>
                                        <td>{{$item->name}}</td>
                                        <td>{{$item->bssid}}</td>
                                        <td>
                                            <a href="javascript:void(0)" class="btn btn-sm btn-info edit-bssid" data-id="{{$item->id}}">Edit</a>
                                            <a href="javascript:void(0)" class="btn btn-sm btn-danger delete-bssid" data-id="{{$item->id}}">Delete</a>
                                        </td>
                                    </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="4" style="text-align: center">Data not found</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- .row -->
    {{-- modal --}}
    <div class="modal fade bs-modal-lg in" id="bssidModal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" id="modal-data-application">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <span class="caption-subject font-red-sunglo bold uppercase" id="modelHeading"></span>
                </div>
                <div class="modal-body">
                    Loading...
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn blue">Save changes</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
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
        $('#save-form').click(function () {
            $.easyAjax({
                url: '{{route('admin.office.update', [$office->id])}}',
                container: '#createCurrency',
                type: "POST",
                redirect: true,
                data: $('#createCurrency').serialize()
            })
        });
        $(document).on('click','#save-form-bssid',function () {
            $.easyAjax({
                url: '{{route('admin.office.store-bssid', [$office->id])}}',
                container: '#createBSSID',
                type: "POST",
                redirect: true,
                data: $('#createBSSID').serialize(),
                success: function(data) {
                    if (data.status=='success') {
                        window.location.reload(false); 
                    }
                }
            })
        });
        $(document).on('click','#update-form-bssid',function () {
            var id = $(this).data('id');
            var url = '{{route('admin.office.update-bssid', ':id')}}';
            url = url.replace(':id', id);
            $.easyAjax({
                url: url,
                container: '#updateBSSID',
                type: "PUT",
                redirect: true,
                data: $('#updateBSSID').serialize(),
                success: function(data) {
                    if (data.status=='success') {
                        window.location.reload(false); 
                    }
                }
            })
        });
        $(document).on('click', '#add-bssid',function () {
            var url = '{!! route('admin.office.create-bssid', $office->id) !!}';

            $('#modelHeading').html('BSSID');
            $.ajaxModal('#bssidModal', url);
            // $(document).find('#custom-datatable').dataTable();
        });
        $(document).on('click', '.edit-bssid',function () {
            // get data
            var id = $(this).data('id');
            var url = '{!! route('admin.office.edit-bssid', ':id') !!}';
            url = url.replace(':id', id);

            $('#modelHeading').html('BSSID');
            $.ajaxModal('#bssidModal', url);
            // $(document).find('#custom-datatable').dataTable();
        });
        $('body').on('click', '.delete-bssid', function(){
            var id = $(this).data('id');
            swal({
                title: "Are you sure?",
                text: "You will not be able to recover the deleted data!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, delete it!",
                cancelButtonText: "No, cancel please!",
                closeOnConfirm: true,
                closeOnCancel: true
            }, function(isConfirm){
                if (isConfirm) {

                    var url = "{{ route('admin.office.delete-bssid',':id') }}";
                    url = url.replace(':id', id);

                    var token = "{{ csrf_token() }}";

                    $.easyAjax({
                        type: 'POST',
                            url: url,
                            data: {'_token': token, '_method': 'DELETE'},
                        success: function (data) {
                            if (data.status=='success') {
                                window.location.reload(false); 
                            }
                        }
                    });
                }
            });
        });
    </script>
@endpush

