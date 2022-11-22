@extends('layouts.app')

@section('page-title')
    <div class="row bg-title">
        <!-- .page title -->
        <div class="col-lg-6 col-md-4 col-sm-4 col-xs-12">
            <h4 class="page-title"><i class="{{ $pageIcon }}"></i> {{ __($pageTitle) }}</h4>
        </div>
        <!-- /.page title -->
        <!-- .breadcrumb -->
        <div class="col-lg-6 col-sm-8 col-md-8 col-xs-12 text-right">
            <a href="{{ route('admin.office.create') }}" class="btn btn-outline btn-success btn-sm">@lang('app.add') @lang('app.menu.office') <i class="fa fa-plus" aria-hidden="true"></i></a>

            <ol class="breadcrumb">
                <li><a href="{{ route('admin.dashboard') }}">@lang('app.menu.home')</a></li>
                <li class="active">{{ __($pageTitle) }}</li>
            </ol>
        </div>
        <!-- /.breadcrumb -->
    </div>
@endsection

@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="white-box">


                <div class="table-responsive">
                    <table class="table table-bordered table-hover toggle-circle default footable-loaded footable" id="users-table">
                        <thead>
                        <tr>
                            <th>@lang('app.id')</th>
                            <th>@lang('app.code')</th>
                            <th>@lang('app.menu.office')</th>
                            <th>@lang('app.latitude')</th>
                            <th>@lang('app.longitude')</th>
                            <th>@lang('modules.attendance.radius')</th>
                            <th>@lang('modules.attendance.jam_istirahat_awal')</th>
                            <th>@lang('modules.attendance.jam_istirahat_akhir')</th>
                            <th>@lang('app.action')</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($office as $item)
                            <tr id="group{{ $item->id }}">
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->code }}</td>
                                <td>{{ $item->name }}</td>
                                <td>{{ $item->latitude }}</td>
                                <td>{{ $item->longitude }}</td>
                                <td>{{ $item->radius }}</td>
                                <td>{{ $item->jam_istirahat_awal }}</td>
                                <td>{{ $item->jam_istirahat_akhir }}</td>
                                <td>

                                    <div class="btn-group dropdown m-r-10">
                                        <button aria-expanded="false" data-toggle="dropdown" class="btn dropdown-toggle waves-effect waves-light" type="button"><i class="ti-more"></i></button>
                                        <ul role="menu" class="dropdown-menu pull-right">
                                            <li><a href="{{ route('admin.office.edit', [$item->id]) }}"><i class="icon-settings"></i> @lang('app.manage')</a></li>
                                            <li><a href="javascript:;"  data-group-id="{{ $item->id }}" class="sa-params"><i class="fa fa-times" aria-hidden="true"></i> @lang('app.delete') </a></li>

                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">
                                    <div class="empty-space" style="height: 200px;">
                                        <div class="empty-space-inner">
                                            <div class="icon" style="font-size:30px"><i
                                                        class="icon-layers"></i>
                                            </div>
                                            <div class="title m-b-15">@lang('messages.noOffice')
                                            </div>
                                            <div class="subtitle">
                                                <a href="{{ route('admin.office.create') }}"
                                                   class="btn btn-outline btn-success btn-sm">@lang('app.add') @lang('app.menu.office')
                                                    <i class="fa fa-plus" aria-hidden="true"></i></a>

                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- .row -->

@endsection

@push('footer-script')
    <script>
        $(function() {


            $('body').on('click', '.sa-params', function(){
                var id = $(this).data('group-id');
                swal({
                    title: "Are you sure?",
                    text: "You will not be able to recover the deleted office!",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Yes, delete it!",
                    cancelButtonText: "No, cancel please!",
                    closeOnConfirm: true,
                    closeOnCancel: true
                }, function(isConfirm){
                    if (isConfirm) {

                        var url = "{{ route('admin.office.destroy',':id') }}";
                        url = url.replace(':id', id);

                        var token = "{{ csrf_token() }}";

                        $.easyAjax({
                            type: 'DELETE',
                            url: url,
                            data: {'_token': token},
                            success: function (response) {
                                if (response.status == "success") {
                                    $.unblockUI();
                                    $('#group'+id).fadeOut();
                                }
                            }
                        });
                    }
                });
            });



        });

    </script>
@endpush
