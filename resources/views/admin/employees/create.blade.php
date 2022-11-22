@extends('layouts.app')

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
                <li><a href="{{ route('admin.employees.index') }}">{{ __($pageTitle) }}</a></li>
                <li class="active">@lang('app.addNew')</li>
            </ol>
        </div>
        <!-- /.breadcrumb -->
    </div>
@endsection

@push('head-script')
<link rel="stylesheet" href="{{ asset('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/bower_components/switchery/dist/switchery.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/bower_components/timepicker/bootstrap-timepicker.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/tagify-master/dist/tagify.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/bower_components/bootstrap-select/bootstrap-select.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/bower_components/custom-select/custom-select.css') }}">
@endpush

@section('content')

    <div class="row">
        <div class="col-md-12">

            <div class="panel panel-inverse">
                <div class="panel-heading"> @lang('modules.employees.createTitle')</div>
                <div class="panel-wrapper collapse in" aria-expanded="true">
                    <div class="panel-body">
                        {!! Form::open(['id'=>'createEmployee','class'=>'ajax-form','method'=>'POST']) !!}
                            <div class="form-body">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="required">@lang('modules.employees.employeeId')</label>
                                            <input type="text" name="employee_id" id="employee_id" class="form-control"
                                                   autocomplete="nope">
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="required">@lang('modules.employees.employeeName')</label>
                                            <input type="text" name="name" id="name" class="form-control" autocomplete="nope">
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="required">@lang('modules.employees.employeeEmail')</label>
                                            <input type="email" name="email" id="email" class="form-control" autocomplete="nope">
                                            <span class="help-block">@lang('modules.employees.emailNote')</span>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="required">@lang('modules.employees.employeePassword')</label>
                                            <input type="password" style="display: none">
                                            <input type="password" name="password" id="password" class="form-control" autocomplete="nope">
                                            <span class="fa fa-fw fa-eye field-icon toggle-password"></span>
                                            <span class="help-block"> @lang('modules.employees.passwordNote') </span>
                                            <div class="checkbox checkbox-info">
                                                <input id="random_password" name="random_password" value="true" type="checkbox">
                                                <label for="random_password">@lang('modules.client.generateRandomPassword')</label>
                                            </div>
                                        </div>
                                    </div>
                                    <!--/span-->

                                    <!--/span-->
                                </div>

                                <!--/row-->

                                <div class="row">
                                    @if (in_array('employees-slack',$modules))
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="control-label"><i class="fa fa-slack"></i> @lang('modules.employees.slackUsername')</label>
                                                <div class="input-group"> <span class="input-group-addon">@</span>
                                                    <input autocomplete="nope" type="text" id="slack_username" name="slack_username" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    <!--/span-->
                                    @if (in_array('employees-join date',$modules))
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="required">@lang('modules.employees.joiningDate')</label>
                                                <input type="text" autocomplete="off"  name="joining_date" id="joining_date" class="form-control">
                                            </div>
                                        </div>
                                    @endif
                                    <!--/span-->

                                    {{-- <div class="col-md-3">
                                        <div class="form-group">
                                            <label>@lang('modules.employees.lastDate')</label>
                                            <input type="text" autocomplete="off" name="last_date" id="end_date" class="form-control">
                                        </div>
                                    </div> --}}

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>@lang('modules.employees.gender')</label>
                                            <select name="gender" id="gender" class="form-control">
                                                <option value="male">@lang('app.male')</option>
                                                <option value="female">@lang('app.female')</option>
                                                <option value="others">@lang('app.others')</option>
                                            </select>
                                        </div>
                                    </div>

                                </div>
                                <!--/row-->

                                <div class="row">
                                    <div class="col-xs-12">
                                        <div class="form-group">
                                            <label class="control-label">@lang('app.address')</label>
                                            <textarea name="address"  id="address"  rows="5" class="form-control"></textarea>
                                        </div>
                                    </div>

                                </div>
                                @if (in_array('employees-skill',$modules))
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>@lang('app.skills')</label>
                                                <input name='tags' placeholder='@lang('app.skills')' value='' >
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                <div class="row">
                                    <div class="col-md-6 ">
                                        <div class="form-group">
                                            <label>@lang('app.subcompany') 
                                                {{-- <a href="javascript:;" id="subcompany-setting" ><i class="ti-settings text-info"></i></a> --}}
                                            </label>
                                            <select name="subcompany" id="subcompany" class="form-control">
                                                <option value="">--</option>
                                                @forelse($subcompanys as $subcompany)
                                                    <option value="{{ $subcompany->id }}">{{ $subcompany->name }}</option>
                                                @empty
                                                    <option value="">@lang('messages.noRecordFound')</option>
                                                @endforelse()
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6 ">
                                        <div class="form-group">
                                            <label>@lang('app.cabang') 
                                                {{-- <a href="javascript:;" id="cabang-setting" ><i class="ti-settings text-info"></i></a> --}}
                                            </label>
                                            <select name="cabang" id="cabang" class="form-control">
                                                <option value="">--</option>
                                                @forelse($cabangs as $cabang)
                                                    <option value="{{ $cabang->id }}">{{ $cabang->name }}</option>
                                                @empty
                                                    <option value="">@lang('messages.noRecordFound')</option>
                                                @endforelse()
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6 ">
                                        <div class="form-group">
                                            <label>@lang('app.wilayah') 
                                                {{-- <a href="javascript:;" id="wilayah-setting" ><i class="ti-settings text-info"></i></a> --}}
                                            </label>
                                            <select name="wilayah" id="wilayah" class="form-control">
                                                <option value="">--</option>
                                                @forelse($wilayahs as $wilayah)
                                                    <option value="{{ $wilayah->id }}">{{ $wilayah->name }}</option>
                                                @empty
                                                    <option value="">@lang('messages.noRecordFound')</option>
                                                @endforelse()
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6 ">
                                        <div class="form-group">
                                            <label>Kapal
                                                {{-- <a href="javascript:;" id="office-setting" ><i class="ti-settings text-info"></i></a> --}}
                                            </label>
                                            <select name="office_id" id="office_id" class="form-control">
                                                <option value="">--</option>
                                                @forelse($office as $off)
                                                    <option value="{{ $off->id }}">{{ $off->name }}</option>
                                                @empty
                                                    <option value="">@lang('messages.noRecordFound')</option>
                                                @endforelse()
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6 ">
                                        <div class="form-group">
                                            <label>@lang('app.department') 
                                                {{-- <a href="javascript:;" id="department-setting" ><i class="ti-settings text-info"></i></a> --}}
                                            </label>
                                            <select name="department" id="department" class="form-control">
                                                <option value="">--</option>
                                                @foreach($teams as $team)
                                                    <option value="{{ $team->id }}">{{ $team->team_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6 ">
                                        <div class="form-group">
                                            <label>@lang('app.designation') 
                                                {{-- <a href="javascript:;" id="designation-setting" ><i class="ti-settings text-info"></i></a> --}}
                                            </label>
                                            <select name="designation" id="designation" class="form-control">
                                                <option value="">--</option>
                                                @forelse($designations as $designation)
                                                    <option value="{{ $designation->id }}">{{ $designation->name }}</option>
                                                @empty
                                                    <option value="">@lang('messages.noRecordFound')</option>
                                                @endforelse()
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6 ">
                                        <div class="form-group">
                                            <label for="">@lang('modules.employees.karyawanKhusus')</label>
                                            <div class="switchery-demo">
                                                @lang('modules.employees.no') <input type="checkbox" class="js-switch assign-role-permission" data-size="small" data-color="#00c292" id="karyawan_khusus" name="karyawan_khusus"/> @lang('modules.employees.yes')
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>@lang('app.menu.cluster_working_hour')</label>
                                            <select name="cluster_working_hour" id="cluster_working_hour" class="form-control">
                                                <option value="">@lang('modules.employees.choose_cluster')</option>
                                                @foreach ($cluster_working_hours as $cluster_working_hour)
                                                    <option value="{{$cluster_working_hour->id}}">{{$cluster_working_hour->name}} ({{$cluster_working_hour->start_hour}} - {{$cluster_working_hour->end_hour}})</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>@lang('app.mobile')</label>
                                            <input type="tel" name="mobile" id="mobile"  class="form-control" autocomplete="nope">
                                        </div>
                                    </div>
                                    @if (in_array('employees-hourly rate',$modules))
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>@lang('modules.employees.hourlyRate')</label>
                                                <input type="text" name="hourly_rate" id="hourly_rate" class="form-control">
                                            </div>
                                        </div>
                                    @endif
                                    <!--/span-->
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>@lang('app.login')</label>
                                            <select name="login" id="login" class="form-control">
                                                <option value="enable">@lang('app.enable')</option>
                                                <option value="disable">@lang('app.disable')</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                {{-- <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <div class="input-group bootstrap-timepicker timepicker">
                                                <label>@lang('modules.attendance.officeStartTime')</label>
                                                <input type="text" name="office_start_time" id="office_start_time" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <div class="input-group bootstrap-timepicker timepicker">
                                                <label>@lang('modules.attendance.officeEndTime')</label>
                                                <input type="text" name="office_end_time" id="office_end_time"
                                                       class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                </div> --}}
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="latitude">Latitude</label>
                                            <input type="text" class="form-control" id="latitude" name="latitude" placeholder="Latitude">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="longitude">Longitude</label>
                                            <input type="text" class="form-control" id="longitude" name="longitude" placeholder="Longitude">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="">@lang('modules.employees.activateUserEditLatLong')</label>
                                        <div class="switchery-demo">
                                            @lang('modules.employees.no') <input type="checkbox" class="js-switch assign-role-permission" data-size="small" data-color="#00c292" id="edit_lat_long" name="edit_lat_long"/> @lang('modules.employees.yes')
                                        </div>
                                    </div>
                                </div><br>
                                <label>@lang('app.menu.permission')</label>
                                <div style="border: solid 1px #eee;padding:10px;">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label>@lang('app.subcompany')</label>
                                            <div class="form-group">
                                                @forelse($subcompanys as $subcompany)
                                                    <div class="checkbox checkbox-inline checkbox-info  col-md-2 m-b-10">
                                                        <input id="{{$subcompany->id.'-subcompany-'.$subcompany->name}}" name="subcompany_rule[]" value="subcompany.{{setPermissionEmployee($subcompany->name)}}" type="checkbox" >
                                                        <label for="{{$subcompany->id.'-subcompany-'.$subcompany->name}}">{{$subcompany->name}}</label>
                                                    </div>
                                                @empty
                                                    @lang('app.noDataFound')
                                                @endforelse()
                                            </div>
                                        </div>
                                    </div><br>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label>@lang('app.wilayah')</label>
                                            <div class="form-group">
                                                @forelse($wilayahs as $wilayah)
                                                    <div class="checkbox checkbox-inline checkbox-info  col-md-2 m-b-10">
                                                        <input id="{{$wilayah->id.'-wilayah-'.$wilayah->name}}" name="wilayah_rule[]" value="wilayah.{{setPermissionEmployee($wilayah->name)}}" type="checkbox">
                                                        <label for="{{$wilayah->id.'-wilayah-'.$wilayah->name}}">{{$wilayah->name}}</label>
                                                    </div>
                                                @empty
                                                    @lang('app.noDataFound')
                                                @endforelse()
                                            </div>
                                        </div>
                                    </div><br>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label>@lang('app.cabang')</label>
                                            <div class="form-group">
                                                @forelse($cabangs as $cabang)
                                                    <div class="checkbox checkbox-inline checkbox-info  col-md-2 m-b-10">
                                                        <input id="{{$cabang->id.'-cabang-'.$cabang->name}}" name="cabang_rule[]" value="cabang.{{setPermissionEmployee($cabang->name)}}" type="checkbox">
                                                        <label for="{{$cabang->id.'-cabang-'.$cabang->name}}">{{$cabang->name}}</label>
                                                    </div>
                                                @empty
                                                    @lang('app.noDataFound')
                                                @endforelse()
                                            </div>
                                        </div>
                                    </div><br>
                                </div><br>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>@lang('modules.employees.persetujuan_1')</label>
                                            <select name="persetujuan_satu" id="persetujuan_satu" class="form-control">
                                                <option value="">@lang('modules.employees.choose_employee')</option>
                                                @foreach ($listEmployee as $item)
                                                    <option value="{{$item->id}}">{{$item->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>@lang('modules.employees.persetujuan_2')</label>
                                            <select name="persetujuan_dua" id="persetujuan_dua" class="form-control select2">
                                                <option value="">@lang('modules.employees.choose_employee')</option>
                                                @foreach ($listEmployee as $item)
                                                    <option value="{{$item->id}}">{{$item->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>@lang('modules.employees.persetujuan_3')</label>
                                            <select name="persetujuan_tiga" id="persetujuan_tiga" class="form-control">
                                                <option value="">@lang('modules.employees.choose_employee')</option>
                                                @foreach ($listEmployee as $item)
                                                    <option value="{{$item->id}}">{{$item->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                {{-- <div class="row">
                                    <div class="col-md-6">
                                        <label for="">@lang('modules.employees.aturTugas')</label>
                                        <div class="switchery-demo">
                                            @lang('modules.employees.no') <input type="checkbox" class="js-switch assign-role-permission" data-size="small" data-color="#00c292" id="atur_tugas" name="atur_tugas"/> @lang('modules.employees.yes')
                                        </div>
                                    </div>
                                </div><br> --}}
                                {{-- <div class="row">
                                    <div class="col-md-6">
                                        <label for="">Orang Kepercayaan</label>
                                        <div class="switchery-demo">
                                            @lang('modules.employees.no') <input type="checkbox" class="js-switch assign-role-permission" data-size="small" data-color="#00c292" id="atur_tugas" name="atur_tugas"/> @lang('modules.employees.yes')
                                        </div>
                                    </div>
                                </div><br> --}}
                                <label>Orang Kepercayaan</label>
                                <button type="button" id="tambah-orang-kepercayaan" class="btn btn-sm btn-primary pull-right">Tambah</button>
                                <div id="container-orang-kepercayaan">
                                    <div class="col-md-12" style="border: solid 1px #eee;padding:10px;margin-bottom: 15px;">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for="">Pilih User</label>
                                                <select id="team_id" name="user_orang_kepercayaan[]" id="orang_kepecayaan" class="form-control">
                                                    @foreach ($listEmployee as $item)
                                                        <option value="{{$item->id}}">{{$item->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>  
                                            <div class="col-md-12">
                                                <label>@lang('app.subcompany')</label>
                                                <div class="form-group">
                                                    @forelse($subcompanys as $subcompany)
                                                        <div class="checkbox checkbox-inline checkbox-info  col-md-2 m-b-10">
                                                            <input id="orang_kepercayaan_subcompany_{{$subcompany->id}}" name="sub_company_orang_kepercayaan[0][]" value="{{$subcompany->id}}" type="checkbox" class="checkbox-sub-company">
                                                            <label for="orang_kepercayaan_subcompany_{{$subcompany->id}}" class="label-sub-company">{{$subcompany->name}}</label>
                                                        </div>
                                                    @empty
                                                        @lang('app.noDataFound')
                                                    @endforelse()
                                                </div>
                                            </div><br>
                                            <div class="col-md-12 active-or-not">
                                                <label>Aktif?</label>
                                                <input id="active" name="active[0][]" type="checkbox">
                                                {{-- <div class="switchery-demo">
                                                    @lang('modules.employees.no') <input type="checkbox" class="js-switch assign-role-permission" data-size="small" data-color="#00c292" id="is_on_orang_kepercayaan" name="is_on_orang_kepercayaan[]"/> @lang('modules.employees.yes')
                                                </div> --}}
                                            </div>
                                        </div><br>
                                    </div><br>
                                </div>
                                <div id="container-clone-orang-kepercayaan"></div>
                                <label>Pengaturan</label>
                                <div class="col-md-12" style="border: solid 1px #eee;padding:10px;margin-bottom: 15px;">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label for="">Pimpinan departemen</label>
                                            <div class="switchery-demo">
                                                @lang('modules.employees.no') <input type="checkbox" class="js-switch assign-role-permission" data-size="small" data-color="#00c292" id="is_atasan" name="is_atasan"/> @lang('modules.employees.yes')
                                            </div>
                                        </div>
                                    </div><br>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label for="">Notifikasi Instant</label>
                                            <div class="switchery-demo">
                                                @lang('modules.employees.no') <input type="checkbox" class="js-switch assign-role-permission" data-size="small" data-color="#00c292" id="notifikasi_instant" name="notifikasi_instant"/> @lang('modules.employees.yes')
                                            </div>
                                        </div>
                                    </div><br>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label for="">@lang('modules.employees.options')</label>
                                            <div class="switchery-demo">
                                                @lang('modules.employees.no') <input type="checkbox" class="js-switch assign-role-permission" data-size="small" data-color="#00c292" id="option_employee" name="option_employee"/> @lang('modules.employees.yes')
                                            </div>
                                            <div id="container_option_employee" style="display: none">
                                                <div class="col-md-12">
                                                    <label>@lang('app.subcompany')</label>
                                                    <div class="form-group">
                                                        @forelse($subcompanys as $subcompany)
                                                            <div class="checkbox checkbox-inline checkbox-info  col-md-2 m-b-10">
                                                                <input id="option_employee_subcompany_{{$subcompany->id}}" name="option_employee_subcompany[]" value="{{$subcompany->id}}" type="checkbox" >
                                                                <label for="option_employee_subcompany_{{$subcompany->id}}">{{$subcompany->name}}</label>
                                                            </div>
                                                        @empty
                                                            @lang('app.noDataFound')
                                                        @endforelse()
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div><br>
                                {{-- <div class="row">
                                    <div class="col-md-6">
                                        <label for="">@lang('modules.employees.menambahkanPengumuman')</label>
                                        <div class="switchery-demo">
                                            @lang('modules.employees.no') <input type="checkbox" class="js-switch assign-role-permission" data-size="small" data-color="#00c292" id="menambahkan_pengumuman" name="menambahkan_pengumuman"/> @lang('modules.employees.yes')
                                        </div>
                                    </div>
                                </div><br> --}}
                                {{-- <div class="row">
                                    <div class="col-md-6">
                                        <label for="">@lang('modules.employees.aturProject')</label>
                                        <div class="switchery-demo">
                                            @lang('modules.employees.no') <input type="checkbox" class="js-switch assign-role-permission" data-size="small" data-color="#00c292" id="atur_project" name="atur_project"/> @lang('modules.employees.yes')
                                        </div>
                                    </div>
                                </div><br> --}}
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label for="">Menerima / Menolak Laporan Tugas</label>
                                            {{-- <label for="">@lang('modules.employees.reportTask')</label> --}}
                                            <div class="switchery-demo">
                                                @lang('modules.employees.no') <input type="checkbox" class="js-switch assign-role-permission" data-size="small" data-color="#00c292" id="report_task" name="report_task"/> @lang('modules.employees.yes')
                                            </div>
                                        </div>
                                    </div><br>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label for="">Jangan Lacak Saya</label>
                                            {{-- <label for="">@lang('modules.employees.reportTask')</label> --}}
                                            <div class="switchery-demo">
                                                @lang('modules.employees.no') <input type="checkbox" class="js-switch assign-role-permission" data-size="small" data-color="#00c292" id="jangan_lacak_saya" name="jangan_lacak_saya"/> @lang('modules.employees.yes')
                                            </div>
                                        </div>
                                    </div>
                                    <small>Ya jika tidak ingin dilacak</small><br><br>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label for="">Akses Delegasi</label>
                                            {{-- <label for="">@lang('modules.employees.reportTask')</label> --}}
                                            <div class="switchery-demo">
                                                @lang('modules.employees.no') <input type="checkbox" class="js-switch assign-role-permission" data-size="small" data-color="#00c292" id="akses_delegasi" name="akses_delegasi"/> @lang('modules.employees.yes')
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <hr>
                                        <div class="row">
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="">List Tugas</label>
                                                    <div class="switchery-demo">
                                                        @lang('modules.employees.no') <input type="checkbox" class="js-switch assign-role-permission" data-size="small" data-color="#00c292" id="list_tugas" name="list_tugas"/> @lang('modules.employees.yes')
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="">Create Tugas</label>
                                                    <div class="switchery-demo">
                                                        @lang('modules.employees.no') <input type="checkbox" class="js-switch assign-role-permission" data-size="small" data-color="#00c292" id="create_tugas" name="create_tugas"/> @lang('modules.employees.yes')
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="">Edit Tugas</label>
                                                    <div class="switchery-demo">
                                                        @lang('modules.employees.no') <input type="checkbox" class="js-switch assign-role-permission" data-size="small" data-color="#00c292" id="edit_tugas" name="edit_tugas"/> @lang('modules.employees.yes')
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="">Hapus Tugas</label>
                                                    <div class="switchery-demo">
                                                        @lang('modules.employees.no') <input type="checkbox" class="js-switch assign-role-permission" data-size="small" data-color="#00c292" id="delete_tugas" name="delete_tugas"/> @lang('modules.employees.yes')
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="row">
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="">List Proyek</label>
                                                    <div class="switchery-demo">
                                                        @lang('modules.employees.no') <input type="checkbox" class="js-switch assign-role-permission" data-size="small" data-color="#00c292" id="list_proyek" name="list_proyek"/> @lang('modules.employees.yes')
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="">Create Proyek</label>
                                                    <div class="switchery-demo">
                                                        @lang('modules.employees.no') <input type="checkbox" class="js-switch assign-role-permission" data-size="small" data-color="#00c292" id="create_proyek" name="create_proyek"/> @lang('modules.employees.yes')
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="">Edit Proyek</label>
                                                    <div class="switchery-demo">
                                                        @lang('modules.employees.no') <input type="checkbox" class="js-switch assign-role-permission" data-size="small" data-color="#00c292" id="edit_proyek" name="edit_proyek"/> @lang('modules.employees.yes')
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="">Hapus Proyek</label>
                                                    <div class="switchery-demo">
                                                        @lang('modules.employees.no') <input type="checkbox" class="js-switch assign-role-permission" data-size="small" data-color="#00c292" id="delete_proyek" name="delete_proyek"/> @lang('modules.employees.yes')
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="row">
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="">List Pengumuman</label>
                                                    <div class="switchery-demo">
                                                        @lang('modules.employees.no') <input type="checkbox" class="js-switch assign-role-permission" data-size="small" data-color="#00c292" id="list_pengumuman" name="list_pengumuman"/> @lang('modules.employees.yes')
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="">Create Pengumuman</label>
                                                    <div class="switchery-demo">
                                                        @lang('modules.employees.no') <input type="checkbox" class="js-switch assign-role-permission" data-size="small" data-color="#00c292" id="create_pengumuman" name="create_pengumuman"/> @lang('modules.employees.yes')
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="">Edit Pengumuman</label>
                                                    <div class="switchery-demo">
                                                        @lang('modules.employees.no') <input type="checkbox" class="js-switch assign-role-permission" data-size="small" data-color="#00c292" id="edit_pengumuman" name="edit_pengumuman"/> @lang('modules.employees.yes')
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="">Hapus Pengumuman</label>
                                                    <div class="switchery-demo">
                                                        @lang('modules.employees.no') <input type="checkbox" class="js-switch assign-role-permission" data-size="small" data-color="#00c292" id="delete_pengumuman" name="delete_pengumuman"/> @lang('modules.employees.yes')
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                        {{-- <label class="control-label col-md-12 p-l-0">@lang('modules.attendance.officeOpenDays')</label> --}}
                                        <div class="row">
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="">List Masalah</label>
                                                    <div class="switchery-demo">
                                                        @lang('modules.employees.no') <input type="checkbox" class="js-switch assign-role-permission" data-size="small" data-color="#00c292" id="list_ticket" name="list_ticket"/> @lang('modules.employees.yes')
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="">Create Masalah</label>
                                                    <div class="switchery-demo">
                                                        @lang('modules.employees.no') <input type="checkbox" class="js-switch assign-role-permission" data-size="small" data-color="#00c292" id="create_ticket" name="create_ticket"/> @lang('modules.employees.yes')
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="">Edit Masalah</label>
                                                    <div class="switchery-demo">
                                                        @lang('modules.employees.no') <input type="checkbox" class="js-switch assign-role-permission" data-size="small" data-color="#00c292" id="edit_ticket" name="edit_ticket"/> @lang('modules.employees.yes')
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="">Hapus Masalah</label>
                                                    <div class="switchery-demo">
                                                        @lang('modules.employees.no') <input type="checkbox" class="js-switch assign-role-permission" data-size="small" data-color="#00c292" id="delete_ticket" name="delete_ticket"/> @lang('modules.employees.yes')
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="">Balas Masalah</label>
                                                    <div class="switchery-demo">
                                                        @lang('modules.employees.no') <input type="checkbox" class="js-switch assign-role-permission" data-size="small" data-color="#00c292" id="reply_ticket" name="reply_ticket"/> @lang('modules.employees.yes')
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                              	    <div class="col-md-6 ">
                                        <div class="form-group">
                                            <label for="">ABK Kapal</label>
                                            <div class="switchery-demo">
                                                @lang('modules.employees.no') <input type="checkbox" class="js-switch assign-role-permission" data-size="small" data-color="#00c292" id="is_abk" name="is_abk"/> @lang('modules.employees.yes')
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 ">
                                        <div class="form-group">
                                            <label for="">HRD Kapal</label>
                                            <div class="switchery-demo">
                                                @lang('modules.employees.no') <input type="checkbox" class="js-switch assign-role-permission" data-size="small" data-color="#00c292" id="is_hrd_kapal" name="is_hrd_kapal"/> @lang('modules.employees.yes')
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 ">
                                        <div class="form-group">
                                            <label for="">PC</label>
                                            <div class="switchery-demo">
                                                @lang('modules.employees.no') <input type="checkbox" class="js-switch assign-role-permission" data-size="small" data-color="#00c292" id="is_pc" name="is_pc"/> @lang('modules.employees.yes')
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 ">
                                        <div class="form-group">
                                            <label for="">PE</label>
                                            <div class="switchery-demo">
                                                @lang('modules.employees.no') <input type="checkbox" class="js-switch assign-role-permission" data-size="small" data-color="#00c292" id="is_pe" name="is_pe"/> @lang('modules.employees.yes')
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 ">
                                        <div class="form-group">
                                            <label for="">Melihat semua proyek</label>
                                            <div class="switchery-demo">
                                                @lang('modules.employees.no') <input type="checkbox" class="js-switch assign-role-permission" data-size="small" data-color="#00c292" id="see_all_project" name="see_all_project"/> @lang('modules.employees.yes')
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="">Perlu Absen</label>
                                        <div class="switchery-demo">
                                            @lang('modules.employees.no') <input type="checkbox" class="js-switch assign-role-permission" data-size="small" data-color="#00c292" id="is_required_absence" name="is_required_absence"/> @lang('modules.employees.yes')
                                        </div>
                                    </div>
                                </div><br>
                                <label>Pengaturan Kapal</label>
                                <div class="col-md-12" style="border: solid 1px #eee;padding:10px;">
                                    <div class="col-md-6 ">
                                        <div class="form-group">
                                            <label for="">Nahkoda</label>
                                            <div class="switchery-demo">
                                                @lang('modules.employees.no') <input type="checkbox" class="js-switch assign-role-permission" data-size="small" data-color="#00c292" id="is_nahkoda" name="is_nahkoda"/> @lang('modules.employees.yes')
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 ">
                                        <div class="form-group">
                                            <label for="">Admin</label>
                                            <div class="switchery-demo">
                                                @lang('modules.employees.no') <input type="checkbox" class="js-switch assign-role-permission" data-size="small" data-color="#00c292" id="is_admin" name="is_admin"/> @lang('modules.employees.yes')
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 ">
                                        <div class="form-group">
                                            <label for="">SPV Pembelian</label>
                                            <div class="switchery-demo">
                                                @lang('modules.employees.no') <input type="checkbox" class="js-switch assign-role-permission" data-size="small" data-color="#00c292" id="is_spv_pembelian" name="is_spv_pembelian"/> @lang('modules.employees.yes')
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 ">
                                        <div class="form-group">
                                            <label for="">Manager</label>
                                            <div class="switchery-demo">
                                                @lang('modules.employees.no') <input type="checkbox" class="js-switch assign-role-permission" data-size="small" data-color="#00c292" id="is_manager" name="is_manager"/> @lang('modules.employees.yes')
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>@lang('modules.profile.profilePicture')</label>
                                        <div class="form-group">
                                            <div class="fileinput fileinput-new" data-provides="fileinput">
                                                <div class="fileinput-new thumbnail" style="width: 200px; height: 150px;">
                                                    <img src="https://via.placeholder.com/200x150.png?text={{ str_replace(' ', '+', __('modules.profile.uploadPicture')) }}"   alt=""/>
                                                </div>
                                                <div class="fileinput-preview fileinput-exists thumbnail"
                                                     style="max-width: 200px; max-height: 150px;"></div>
                                                <div>
                                <span class="btn btn-info btn-file">
                                    <span class="fileinput-new"> @lang('app.selectImage') </span>
                                    <span class="fileinput-exists"> @lang('app.change') </span>
                                    <input type="file" id="image" name="image"> </span>
                                                    <a href="javascript:;" class="btn btn-danger fileinput-exists"
                                                       data-dismiss="fileinput"> @lang('app.remove') </a>
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                </div>
                                <!--/span-->

                                <div class="row">
                                    @if(isset($fields))
                                        @foreach($fields as $field)
                                            <div class="col-md-6">
                                                <label>{{ ucfirst($field->label) }}</label>
                                                <div class="form-group">
                                                    @if( $field->type == 'text')
                                                        <input type="text" name="custom_fields_data[{{$field->name.'_'.$field->id}}]" class="form-control" placeholder="{{$field->label}}" value="{{$editUser->custom_fields_data['field_'.$field->id] ?? ''}}">
                                                    @elseif($field->type == 'password')
                                                        <input type="password" name="custom_fields_data[{{$field->name.'_'.$field->id}}]" class="form-control" placeholder="{{$field->label}}" value="{{$editUser->custom_fields_data['field_'.$field->id] ?? ''}}">
                                                    @elseif($field->type == 'number')
                                                        <input type="number" name="custom_fields_data[{{$field->name.'_'.$field->id}}]" class="form-control" placeholder="{{$field->label}}" value="{{$editUser->custom_fields_data['field_'.$field->id] ?? ''}}">

                                                    @elseif($field->type == 'textarea')
                                                        <textarea name="custom_fields_data[{{$field->name.'_'.$field->id}}]" class="form-control" id="{{$field->name}}" cols="3">{{$editUser->custom_fields_data['field_'.$field->id] ?? ''}}</textarea>

                                                    @elseif($field->type == 'radio')
                                                        <div class="radio-list">
                                                            @foreach($field->values as $key=>$value)
                                                            <label class="radio-inline @if($key == 0) p-0 @endif">
                                                                <div class="radio radio-info">
                                                                    <input type="radio" name="custom_fields_data[{{$field->name.'_'.$field->id}}]" id="optionsRadios{{$key.$field->id}}" value="{{$value}}" @if(isset($editUser) && $editUser->custom_fields_data['field_'.$field->id] == $value) checked @elseif($key==0) checked @endif>>
                                                                    <label for="optionsRadios{{$key.$field->id}}">{{$value}}</label>
                                                                </div>
                                                            </label>
                                                            @endforeach
                                                        </div>
                                                    @elseif($field->type == 'select')
                                                        {!! Form::select('custom_fields_data['.$field->name.'_'.$field->id.']',
                                                                $field->values,
                                                                 isset($editUser)?$editUser->custom_fields_data['field_'.$field->id]:'',['class' => 'form-control gender'])
                                                         !!}

                                                    @elseif($field->type == 'checkbox')
                                                        <div class="mt-checkbox-inline">
                                                            @foreach($field->values as $key => $value)
                                                                <label class="mt-checkbox mt-checkbox-outline">
                                                                    <input name="custom_fields_data[{{$field->name.'_'.$field->id}}][]" type="checkbox" value="{{$key}}"> {{$value}}
                                                                    <span></span>
                                                                </label>
                                                            @endforeach
                                                        </div>
                                                    @elseif($field->type == 'date')
                                                        <input type="text" class="form-control date-picker" size="16" name="custom_fields_data[{{$field->name.'_'.$field->id}}]"
                                                                value="{{ isset($editUser->dob)?Carbon\Carbon::parse($editUser->dob)->format('Y-m-d'):Carbon\Carbon::now()->format($global->date_format)}}">
                                                    @endif
                                                    <div class="form-control-focus"> </div>
                                                    <span class="help-block"></span>

                                                </div>
                                            </div>
                                        @endforeach
                                    @endif

                                </div>


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
    {{--Ajax Modal--}}
    <div class="modal fade bs-modal-md in" id="departmentModel" role="dialog" aria-labelledby="myModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-md" id="modal-data-application">
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
    {{--Ajax Modal Ends--}}
@endsection

@push('footer-script')
<script src="{{ asset('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/switchery/dist/switchery.min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/timepicker/bootstrap-timepicker.min.js') }}"></script>
<script src="{{ asset('plugins/tagify-master/dist/tagify.js') }}"></script>
<script src="{{ asset('plugins/bower_components/bootstrap-select/bootstrap-select.min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/custom-select/custom-select.min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/bootstrap-select/bootstrap-select.min.js') }}"></script>
<script data-name="basic">
    var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));
    $('.js-switch').each(function() {
        new Switchery($(this)[0], $(this).data());
    });
    (function(){
        $("#persetujuan_satu").select2({
            formatNoMatches: function () {
                return "{{ __('messages.noRecordFound') }}";
            }
        });
        $("#persetujuan_dua").select2({
            formatNoMatches: function () {
                return "{{ __('messages.noRecordFound') }}";
            }
        });
        $("#persetujuan_tiga").select2({
            formatNoMatches: function () {
                return "{{ __('messages.noRecordFound') }}";
            }
        });
        $("#orang_kepecayaan").select2({
            formatNoMatches: function () {
                return "{{ __('messages.noRecordFound') }}";
            }
        });
        $("#department").select2({
            formatNoMatches: function () {
                return "{{ __('messages.noRecordFound') }}";
            }
        });
        $("#designation").select2({
            formatNoMatches: function () {
                return "{{ __('messages.noRecordFound') }}";
            }
        });
        $("#subcompany").select2({
            formatNoMatches: function () {
                return "{{ __('messages.noRecordFound') }}";
            }
        });
        $("#cabang").select2({
            formatNoMatches: function () {
                return "{{ __('messages.noRecordFound') }}";
            }
        });
        $("#wilayah").select2({
            formatNoMatches: function () {
                return "{{ __('messages.noRecordFound') }}";
            }
        });
        var input = document.querySelector('input[name=tags]'),
            // init Tagify script on the above inputs
            tagify = new Tagify(input, {
                whitelist : {!! json_encode($skills) !!},
                //  blacklist : [".NET", "PHP"] // <-- passed as an attribute in this demo
            });

// Chainable event listeners
        tagify.on('add', onAddTag)
            .on('remove', onRemoveTag)
            .on('input', onInput)
            .on('invalid', onInvalidTag)
            .on('click', onTagClick);

// tag added callback
        function onAddTag(e){
            tagify.off('add', onAddTag) // exmaple of removing a custom Tagify event
        }

// tag remvoed callback
        function onRemoveTag(e){
        }

// on character(s) added/removed (user is typing/deleting)
        function onInput(e){
        }

// invalid tag added callback
        function onInvalidTag(e){
        }

// invalid tag added callback
        function onTagClick(e){
        }

    })()
</script>

<script>
    $('#office_end_time, #office_start_time, #halfday_mark_time').timepicker({
        @if($global->time_format == 'H:i')
        showMeridian: false
        @endif
    });
    $("#joining_date, #end_date").datepicker({
        todayHighlight: true,
        autoclose: true,
        weekStart:'{{ $global->week_start }}',
        format: '{{ $global->date_picker_format }}',
    });

    $('#save-form').click(function () {
        $.easyAjax({
            url: '{{route('admin.employees.store')}}',
            container: '#createEmployee',
            type: "POST",
            redirect: true,
            file: (document.getElementById("image").files.length == 0) ? false : true,
            data: $('#createEmployee').serialize()
        })
    });

    $('#random_password').change(function () {
        var randPassword = $(this).is(":checked");

        if(randPassword){
            $('#password').val('{{ str_random(8) }}');
            $('#password').attr('readonly', 'readonly');
        }
        else{
            $('#password').val('');
            $('#password').removeAttr('readonly');
        }
    });

    $('#department-setting').on('click', function (event) {
        event.preventDefault();
        var url = '{{ route('admin.teams.quick-create')}}';
        $('#modelHeading').html("@lang('messages.manageDepartment')");
        $.ajaxModal('#departmentModel', url);
    });

    $('#designation-setting').on('click', function (event) {
        event.preventDefault();
        var url = '{{ route('admin.designations.quick-create')}}';
        $('#modelHeading').html("@lang('messages.manageDepartment')");
        $.ajaxModal('#departmentModel', url);
    });
    
    $('#subcompany-setting').on('click', function (event) {
        event.preventDefault();
        var url = '{{ route('admin.subcompany.quick-create')}}';
        $('#modelHeading').html("@lang('messages.manageDepartment')");
        $.ajaxModal('#departmentModel', url);
    });

    $('#cabang-setting').on('click', function (event) {
        event.preventDefault();
        var url = '{{ route('admin.cabang.quick-create')}}';
        $('#modelHeading').html("@lang('messages.manageDepartment')");
        $.ajaxModal('#departmentModel', url);
    });
    $('#wilayah-setting').on('click', function (event) {
        event.preventDefault();
        var url = '{{ route('admin.wilayah.quick-create')}}';
        $('#modelHeading').html("@lang('messages.manageDepartment')");
        $.ajaxModal('#departmentModel', url);
    });
    $("#option_employee").on('change', function(){
        var status = $(this).is(':checked');
        if (status) {
            $("#container_option_employee").show();
            var list = $("#all_option_employee").val();
        }else{
            $("#container_option_employee").hide();
        }
    })
    $("#tambah-orang-kepercayaan").on('click', function(){
        var element = $("#container-orang-kepercayaan").clone();
        element.children('div').prepend("<button type='button' class='delete-me btn btn-sm btn-danger pull-right'>Delete</>");
        var input = element.find(".checkbox-sub-company");
        var label = element.find(".label-sub-company");
        var counter = $("#container-clone-orang-kepercayaan #container-orang-kepercayaan").length+1;
        element.find(".active-or-not input").attr('name',`active[${counter}][]`)
        $.each(input, function( index, value ) {
            $(value).attr('id',$(value).attr('id')+'_'+counter)
            $(value).attr('name',`sub_company_orang_kepercayaan[${counter}][]`)
        });
        $.each(label, function( index, value ) {
            $(value).attr('for',$(value).attr('for')+'_'+counter)
        });
        $("#container-clone-orang-kepercayaan").append(element);
    })
    $(document).on('click',".delete-me", function(){
        $(this).parent().remove();
    })
</script>
@endpush

