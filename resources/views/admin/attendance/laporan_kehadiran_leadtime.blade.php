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
                        <li><a href="{{ route('admin.attendances.laporan') }}"><span>Laporan</span></a>
                        </li>
                        <li><a href="{{ route('admin.attendances.laporanKehadiran') }}"><span>Laporan Kehadiran</span></a>
                        </li>
                        <li class="tab-current"><a href="{{ route('admin.attendances.laporanKehadiranLeadtime') }}"><span>Laporan Ijin Leadtime</span></a>
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
                            <label class="control-label">Wilayah</label>
                            <select class="select2 form-control" data-placeholder="Choose Wilayah" id="wilayah" name="wilayah">
                                <option value="0">--</option>
                                @foreach($wilayahs as $wilayah)
                                    <option value="{{ $wilayah->id }}">{{ ucwords($wilayah->name) }}</option>
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
                  
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label">Pembuat Ijin</label>
                            <select class="select2 form-control" data-placeholder="Choose Employee" id="pembuat_ijin" name="pembuat_ijin">
                                <option value="0">--</option>
                                @foreach($employees as $employee)
                                    {{-- @if ($loop->index==0)
                                        <option value="{{ $employee->id }}" selected>{{ ucwords($employee->name) }}</option>
                                    @else    
                                        <option value="{{ $employee->id }}">{{ ucwords($employee->name) }}</option>
                                    @endif --}}
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
                        <div class="form-group">
                            <label class="control-label">Tanggal Mulai Pembuatan Ijin</label>
                            <input type="text" autocomplete="off"  name="tanggal_mulai_pembuatan_ijin" id="tanggal_mulai_pembuatan_ijin" class="form-control" value="{{$init_start}}">
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group">
                            <label class="control-label">Tanggal Berahkir Pembuatan Ijin</label>
                            <input type="text" autocomplete="off"  name="tanggal_berakhir_pembuatan_ijin" id="tanggal_berakhir_pembuatan_ijin" class="form-control" value="{{$init_end}}">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label class="control-label">Status</label>
                            <select class="select2 form-control" data-placeholder="Choose . . ." id="status" name="status">
                                <option value="0">--</option>
                                <option value="pending">Pending</option>
                                <option value="in progress">In Progress</option>
                                <option value="done">Done</option>
                            </select>
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
  
    $('#apply-filter').click(function () {
        // validate data
        var department = $('#department').val();
        var subcompany = $('#subcompany').val();
        var arr=[];
        // if (department=='0') {
        //     arr.push('*Departemen diperlukan');
        // }
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
        var tanggal_mulai_pembuatan_ijin = $('#tanggal_mulai_pembuatan_ijin').val();
        var tanggal_berakhir_pembuatan_ijin = $('#tanggal_berakhir_pembuatan_ijin').val();
        var pembuatIjin = $('#pembuat_ijin').val();
        var status = $('#status').val();
        var atasan_1 = $('#atasan_1').val();
        var atasan_2 = $('#atasan_2').val();
        var hrd = $('#hrd').val();
        var department = $('#department').val();
        var subcompany = $('#subcompany').val();
        var wilayah = $('#wilayah').val();
        var office_id = $('#office_id').val();

        var url = '{!! route('admin.attendances.exportExcelLaporanKehadiranLeadtime', [':tanggal_mulai_pembuatan_ijin',':tanggal_berakhir_pembuatan_ijin',':pembuatIjin',':subcompany',':wilayah',':department',':status',':office_id']) !!}';
        url = url.replace(':tanggal_mulai_pembuatan_ijin', tanggal_mulai_pembuatan_ijin);
        url = url.replace(':tanggal_berakhir_pembuatan_ijin', tanggal_berakhir_pembuatan_ijin);
        url = url.replace(':pembuatIjin', pembuatIjin);
        url = url.replace(':department', department);
        url = url.replace(':status', status);
        url = url.replace(':subcompany', subcompany);
        url = url.replace(':wilayah', wilayah);
        url = url.replace(':office_id', office_id);
        window.open(url);
    });

    $(".select2").select2({
        formatNoMatches: function () {
            return "{{ __('messages.noRecordFound') }}";
        }
    });

    $("#tanggal_mulai_pembuatan_ijin, #tanggal_berakhir_pembuatan_ijin").datepicker({
        todayHighlight: true,
        autoclose: true,
        weekStart:'{{ $global->week_start }}',
        format: '{{ $global->date_picker_format }}',
    });

    function showTable() {

        var tanggal_mulai_pembuatan_ijin = $('#tanggal_mulai_pembuatan_ijin').val();
        var tanggal_berakhir_pembuatan_ijin = $('#tanggal_berakhir_pembuatan_ijin').val();
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

        var pembuatIjin = $('#pembuat_ijin').val();
        var status = $('#status').val();
        var atasan_1 = $('#atasan_1').val();
        var atasan_2 = $('#atasan_2').val();
        var hrd = $('#hrd').val();
        var department = $('#department').val();
        var subcompany = $('#subcompany').val();
        var wilayah = $('#wilayah').val();
        var office_id = $('#office_id').val();
        var libur = $('#libur').val();
      
        //refresh counts
        var url = '{!!  route('admin.attendances.laporanKehadiranLeadtimeData') !!}';

        var token = "{{ csrf_token() }}";

        $.easyAjax({
            type: 'POST',
            data: {
                '_token': token,
                tanggal_mulai_pembuatan_ijin: tanggal_mulai_pembuatan_ijin,
                tanggal_berakhir_pembuatan_ijin: tanggal_berakhir_pembuatan_ijin,
                pembuatIjin: pembuatIjin,
                status: status,
                atasan_1: atasan_1,
                atasan_2: atasan_2,
                hrd: hrd,
                department: department,
                wilayah: wilayah,
                subcompany: subcompany,
                office_id: office_id,
                libur: libur
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