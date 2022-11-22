@extends('layouts.app')

@section('page-title')
    <div class="row bg-title">
        <!-- .page title -->
        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
            <h4 class="page-title"><i class="{{ $pageIcon }}"></i> Laporan Leadtime Pengecekan Tugas</h4>
        </div>
        <!-- /.page title -->
        <!-- .breadcrumb -->
        <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12 text-right">
            {{-- <a href="{{ route('admin.attendances.create') }}"
            class="btn btn-success btn-outline btn-sm">@lang('modules.attendance.markAttendance') <i class="fa fa-plus"  aria-hidden="true"></i></a> --}}
            <ol class="breadcrumb">
                <li><a href="{{ route('admin.dashboard') }}">@lang('app.menu.home')</a></li>
                <li class="active">Laporan Leadtime Pengecekan Tugas</li>
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
        <div class="col-md-12">
            <div class="white-box p-b-0">
                <div class="row">
					
                    <div class="col-md-2">
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
                  
                    <div class="col-md-2">
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
                    <div class="col-md-2">
                        <div class="form-group">
                            <label class="control-label">Tanggal Mulai Pembuatan Tugas</label>
                            <input type="text" autocomplete="off"  name="tanggal_mulai_pembuatan_tugas" id="tanggal_mulai_pembuatan_tugas" class="form-control" value="{{$init_date}}">
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group">
                            <label class="control-label">Tanggal Berahkir Pembuatan Tugas</label>
                            <input type="text" autocomplete="off"  name="tanggal_berakhir_pembuatan_tugas" id="tanggal_berakhir_pembuatan_tugas" class="form-control" value="{{$init_date}}">
                        </div>
                    </div>
                    
                    <div class="col-md-2">
                        <div class="form-group m-t-20">
                            <button type="button" id="apply-filter" class="btn btn-info btn-block">@lang('app.apply')</button>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group m-t-20">
                            <button type="button" id="export-excel" class="btn btn-info btn-block">Export Excel</button>
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
  	$('document').ready(function(){
      if(is_admin_office == "1"){
      	$('#subcompany').val(admin_subcompany).trigger('change').prop('disabled', true);
      }
    });

    $("#tanggal_mulai_pembuatan_tugas, #tanggal_berakhir_pembuatan_tugas").datepicker({
        todayHighlight: true,
        autoclose: true,
        weekStart:'{{ $global->week_start }}',
        format: '{{ $global->date_picker_format }}',
    });
  
    $('#apply-filter').click(function () {
        // validate data
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
    $('#export-excel').click(function () {
        var tanggal_mulai_pembuatan_tugas = $('#tanggal_mulai_pembuatan_tugas').val();
        var tanggal_berakhir_pembuatan_tugas = $('#tanggal_berakhir_pembuatan_tugas').val();
        var subcompany = $('#subcompany').val();
        var department = $('#department').val();

        var url = '{!! route('admin.task-report.leadtime-pengecekan-tugas.export', [':subcompany',':department',':tanggal_mulai_pembuatan_tugas',':tanggal_berakhir_pembuatan_tugas']) !!}';
        url = url.replace(':subcompany', subcompany);
        url = url.replace(':department', department);
        url = url.replace(':tanggal_mulai_pembuatan_tugas', tanggal_mulai_pembuatan_tugas);
        url = url.replace(':tanggal_berakhir_pembuatan_tugas', tanggal_berakhir_pembuatan_tugas);
        window.open(url);
    });

    $(".select2").select2({
        formatNoMatches: function () {
            return "{{ __('messages.noRecordFound') }}";
        }
    });

    function showTable() {
        $('body').block({
            message: '<p style="margin:0;padding:8px;font-size:24px;">Just a moment...</p>'
            , css: {
                color: '#fff'
                , border: '1px solid #fb9678'
                , backgroundColor: '#fb9678'
            }
        });
        var tanggal_mulai_pembuatan_tugas = $('#tanggal_mulai_pembuatan_tugas').val();
        var tanggal_berakhir_pembuatan_tugas = $('#tanggal_berakhir_pembuatan_tugas').val();
        var department = $('#department').val();
        var subcompany = $('#subcompany').val();
      
        //refresh counts
        var url = '{!!  route('admin.task-report.leadtime-pengecekan-tugas.data') !!}';

        var token = "{{ csrf_token() }}";

        $.easyAjax({
            type: 'POST',
            data: {
                '_token': token,
                tanggal_mulai_pembuatan_tugas: tanggal_mulai_pembuatan_tugas,
                tanggal_berakhir_pembuatan_tugas: tanggal_berakhir_pembuatan_tugas,
                department: department,
                subcompany: subcompany
            },
            url: url,
            success: function (response) {
               $('#attendance-data').html(response.data);
               $('#custom-datatable').dataTable();
            }
        });

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
    })
</script>
@endpush