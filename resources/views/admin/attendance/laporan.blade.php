@extends('layouts.app')

@section('page-title')
    <div class="row bg-title">
        <!-- .page title -->
        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
            <h4 class="page-title"><i class="{{ $pageIcon }}"></i> {{ __($pageTitle) }}</h4>
        </div>
        <!-- /.page title -->
        <!-- .breadcrumb -->
        <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12 text-right">
            {{-- <a href="{{ route('admin.attendances.create') }}"
            class="btn btn-success btn-outline btn-sm">@lang('modules.attendance.markAttendance') <i class="fa fa-plus"  aria-hidden="true"></i></a> --}}
            <ol class="breadcrumb">
                <li><a href="{{ route('admin.dashboard') }}">@lang('app.menu.home')</a></li>
                <li class="active">{{ __($pageTitle) }}</li>
            </ol>
        </div>
        <!-- /.breadcrumb -->
    </div>
@endsection

@push('head-script')
<link rel="stylesheet" href="{{ asset('plugins/bower_components/custom-select/custom-select.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/bower_components/bootstrap-select/bootstrap-select.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/bower_components/timepicker/bootstrap-timepicker.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/bower_components/switchery/dist/switchery.min.css') }}">
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.13/css/dataTables.bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.1.1/css/responsive.bootstrap.min.css">
<link rel="stylesheet" href="//cdn.datatables.net/buttons/1.2.2/css/buttons.dataTables.min.css">
@endpush

@section('content')
    <div class="row">
   

        <div class="sttabs tabs-style-line col-md-12">
            <div class="white-box">
                <nav>
                    <ul>
                        {{-- <li><a href="{{ route('admin.attendances.summary') }}"><span>@lang('app.summary')</span></a>
                        </li>
                        <li><a href="{{ route('admin.attendances.index') }}"><span>@lang('modules.attendance.attendanceByMember')</span></a>
                        </li>
                        <li><a href="{{ route('admin.attendances.attendanceByDate') }}"><span>@lang('modules.attendance.attendanceByDate')</span></a>
                        </li> --}}
                        <li class="tab-current"><a href="{{ route('admin.attendances.laporan') }}"><span>Laporan</span></a>
                        </li>
                        <li><a href="{{ route('admin.attendances.laporanKehadiran') }}"><span>Laporan Kehadiran</span></a>
                        </li>
                        <li><a href="{{ route('admin.attendances.laporanKehadiranLeadtime') }}"><span>Laporan Ijin Leadtime</span></a>
                        </li>

                    </ul>
                </nav>
            </div>
        </div>
    </div>
    <!-- .row -->

    <div class="row">
        <div class="col-md-12">
            <div class="white-box p-b-0">
                <div class="row">
                  
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label">Anak Perusahaan</label>
                            <select class="select2 form-control" data-placeholder="Choose Employee" id="subcompany" name="subcompany">
                                <option value="0">--</option>
                                @foreach($subcompanies as $subcompanie)
                                    <option value="{{ $subcompanie->id }}">{{ ucwords($subcompanie->name) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                  
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label">Wilayah</label>
                            <select class="select2 form-control" data-placeholder="Choose Wilayah" id="wilayah" name="wilayah">
                                <option value="0">--</option>
                                @foreach($wilayahs as $wilayah)
                                    <option value="{{ $wilayah->id }}">{{ ucwords($wilayah->name) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                  <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label">Department</label>
                            <select class="select2 form-control" data-placeholder="Choose Employee" id="department" name="department">
                                <option value="0">--</option>
                                @foreach($teams as $team)
                                    <option value="{{ $team->id }}">{{ ucwords($team->team_name) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                  
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label">@lang('modules.timeLogs.employeeName')</label>
                            <select class="select2 form-control" data-placeholder="Choose Employee" id="user_id" name="user_id">
                                <option value="0">--</option>
                                @foreach($employees as $employee)
                                        <option value="{{ $employee->id }}">{{ ucwords($employee->name) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label">Kapal</label>
                            <select class="select2 form-control" data-placeholder="Choose Employee" id="office_id" name="office_id">
                                <option value="0">--</option>
                                @foreach($kapal as $item)
                                    <option value="{{ $item->id }}">{{ ucwords($item->name) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>


                    <div class="col-md-2">
                        <div class="form-group m-t-20">
                            <button type="button" id="apply-filter" class="btn btn-info btn-block">@lang('app.apply')</button>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group">
                            <label class="control-label">@lang('app.select') @lang('app.startDate')</label>
                            <input type="text" autocomplete="off"  name="start_date" id="start_date" class="form-control" value="{{$init_start}}">
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group">
                            <label class="control-label">@lang('app.select') @lang('app.endDate')</label>
                            <input type="text" autocomplete="off"  name="end_date" id="end_date" class="form-control" value="{{$init_end}}">
                        </div>
                    </div>

                </div>

            </div>
        </div>


    </div>

    <div class="row">
        <div class="col-md-12" id="attendance-data"></div>
    </div>

    {{--Timer Modal--}}
    <div class="modal fade bs-modal-lg in" id="attendanceModal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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
    {{--Timer Modal Ends--}}

@endsection

@push('footer-script')
<script src="{{ asset('plugins/bower_components/custom-select/custom-select.min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/bootstrap-select/bootstrap-select.min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/timepicker/bootstrap-timepicker.min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/switchery/dist/switchery.min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/datatables/jquery.dataTables.min.js') }}"></script>
<script src="https://cdn.datatables.net/1.10.13/js/dataTables.bootstrap.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.1.1/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.1.1/js/responsive.bootstrap.min.js"></script>

<script>
    
    $('#apply-filter').click(function () {
        var department = $('#department').val();
        var subcompany = $('#subcompany').val();
        var arr=[];
        if (department=='0') {
            arr.push('*Departemen diperlukan');
        }
        if (subcompany=='0') {
            arr.push('*Anak perusahaan diperlukan');
        }
        if (arr.length>0) {
            alert(arr.join('\n'));
        }else{
            showTable();
        }
    });

    $(".select2").select2({
        formatNoMatches: function () {
            return "{{ __('messages.noRecordFound') }}";
        }
    });

    $("#start_date, #end_date").datepicker({
        todayHighlight: true,
        autoclose: true,
        weekStart:'{{ $global->week_start }}',
        format: '{{ $global->date_picker_format }}',
    });

    function showTable() {

        var start_date = $('#start_date').val();
        var end_date = $('#end_date').val();
        // var year = $('#year').val();
        // var month = $('#month').val();

        $('body').block({
            message: '<p style="margin:0;padding:8px;font-size:24px;">Just a moment...</p>'
            , css: {
                color: '#fff'
                , border: '1px solid #fb9678'
                , backgroundColor: '#fb9678'
            }
        });

        var userId = $('#user_id').val();
        var department = $('#department').val();
        var subcompany = $('#subcompany').val();
        var office_id = $('#office_id').val();
        var wilayah = $('#wilayah').val();
      
        //refresh counts
        var url = '{!!  route('admin.attendances.laporanData') !!}';

        var token = "{{ csrf_token() }}";

        $.easyAjax({
            type: 'POST',
            data: {
                '_token': token,
                start_date: start_date,
                end_date: end_date,
                userId: userId,
                department: department,
                wilayah: wilayah,
                subcompany: subcompany,
                office_id: office_id
            },
            url: url,
            success: function (response) {
               $('#attendance-data').html(response.data);
               $('#custom-datatable').dataTable();
            }
        });

    }

    // showTable();
  
  	$('document').ready(function(){
      	if(is_admin_office){
    		$('#subcompany').val(admin_subcompany).trigger('change').prop('disabled', true);
        }
    });

    $(document).on('click', '.view-attendance',function () {
        var attendanceID = $(this).data('attendance-id');
        var url = '{!! route('admin.attendances.info', ':attendanceID') !!}';
        url = url.replace(':attendanceID', attendanceID);
        
        $('#modelHeading').html('{{__("app.menu.attendance") }}');
        $.ajaxModal('#projectTimerModal', url);
    });
    $('#attendance-data').on('click', '.view-ijin',function () {
        var attendanceID = $(this).data('leave');
        attendanceID = attendanceID.replaceAll(/\s/g,'');
        // console.log(attendanceID);
        var url = '{!! route('admin.attendances.info', ':attendanceID') !!}';
        url = url.replace(':attendanceID', attendanceID);
        // console.log(url);
        $('#modelHeading').html('{{__("app.menu.attendance") }}');
        $.ajaxModal('#projectTimerModal', url);
    });

    $('#attendance-data').on('click', '.edit-attendance',function (event) {
        $(this).tooltip();
        var attendanceDate = $(this).data('attendance-date');
        var userData       = $(this).closest('tr').children('td:first');
        var userID         = userData[0]['firstChild']['nextSibling']['dataset']['employeeId'];
        console.log(userData,userID);

        var url = '{!! route('admin.attendances.mark', [':userid',':date']) !!}';
        url = url.replace(':userid', userID);
        url = url.replace(':date', attendanceDate);

        $('#modelHeading').html('{{__("app.menu.attendance") }}');
        $.ajaxModal('#projectTimerModal', url);
    });

    function editAttendance (id) {
        $('#projectTimerModal').modal('hide');

        var url = '{!! route('admin.attendances.edit', [':id']) !!}';
        url = url.replace(':id', id);
        console.log('sri ram');
        $('#modelHeading').html('{{__("app.menu.attendance") }}');
        $.ajaxModal('#attendanceModal', url);
    }
  
  
     $('#subcompany').on('change', function() {
      	var subcompany_id = $('#subcompany').val();
       
        var url = '{!!  route('admin.attendances.getDepartmentBySubCompany') !!}';

        var token = "{{ csrf_token() }}";

        $.easyAjax({
            type: 'POST',
            data: {
                '_token': token,
                subcompany_id: subcompany_id,
            },
            url: url,
            success: function (response) {
               	var options = response.data;
               	$("#department").empty();
               	var newOption = new Option("--", 0, false, false);
               	$('#department').append(newOption).trigger('change');
               	for (i = 0; i < options.length; i++) {
                	var newOption = new Option(options[i].text, options[i].id, false, false);
                	$('#department').append(newOption);
                
            	}
            }
        });


        var url = '{!!  route('admin.attendances.getWilayahBySubCompany') !!}';

        var token = "{{ csrf_token() }}";

        $.easyAjax({
            type: 'POST',
            data: {
                '_token': token,
                subcompany_id: subcompany_id,
            },
            url: url,
            success: function (response) {
                var options = response.data;
                $("#wilayah").empty();
                var newOption = new Option("--", 0, false, false);
                $('#wilayah').append(newOption).trigger('change');
                for (i = 0; i < options.length; i++) {
                    var newOption = new Option(options[i].text, options[i].id, false, false);
                    $('#wilayah').append(newOption);
                
                }
            }
        });
        
       
    })
     
</script>
@endpush