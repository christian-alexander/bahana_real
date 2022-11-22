@extends('layouts.member-app')
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
            <li><a href="{{ route('member.dashboard') }}">@lang('app.menu.home')</a></li>
            <li><a href="{{ route('member.abk.index') }}">{{ __($pageTitle) }}</a></li>
            <li class="active">@lang('app.edit')</li>
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
<link rel="stylesheet" href="{{ asset('plugins/bower_components/bootstrap-select/bootstrap-select.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/bower_components/custom-select/custom-select.css') }}">
@endpush
@section('content')

<div class="row">
    <div class="col-md-12">

        <div class="panel panel-inverse">
            <div class="panel-heading"> @lang('modules.employees.updateTitle')
                [ {{ $userDetail->name }} ]
                @php($class = ($userDetail->status == 'active') ? 'label-custom' : 'label-danger')
                <span class="label {{$class}}">{{ucfirst($userDetail->status)}}</span>
            </div>
            <div class="panel-wrapper collapse in" aria-expanded="true">
                <div class="panel-body">
                    {!! Form::open(['id'=>'updateEmployee','class'=>'ajax-form','method'=>'PUT']) !!}
                    <div class="form-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="required">@lang('modules.employees.employeeId')</label>
                                    <input type="text" name="employee_id" id="employee_id" class="form-control"
                                           value="{{ $employeeDetail->employee_id }}" autocomplete="nope">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="required">@lang('modules.employees.employeeName')</label>
                                    <input type="text" name="name" id="name" class="form-control" value="{{ $userDetail->name }}" autocomplete="nope">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="required">@lang('modules.employees.employeeEmail')</label>
                                    <input type="email" name="email" id="email" class="form-control" value="{{ $userDetail->email }}" autocomplete="nope">
                                    <span class="help-block">Employee will login using this email.</span>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="required">@lang('modules.employees.employeePassword')</label>
                                    <input type="password" style="display: none">
                                    <input type="password" name="password" id="password" class="form-control" autocomplete="nope">
                                    <span class="help-block"> @lang('modules.employees.updatePasswordNote')</span>
                                </div>
                            </div>
                            <!--/span-->
                        </div>

                        <div class="row">
                            @if (in_array('employees-slack',$modules))
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="control-label"><i
                                                        class="fa fa-slack"></i> @lang('modules.employees.slackUsername')
                                            </label>
                                        <div class="input-group"><span class="input-group-addon">@</span>
                                            <input type="text" id="slack_username" name="slack_username" class="form-control" autocomplete="nope" value="{{ $employeeDetail->slack_username ?? '' }}">
                                        </div>
                                    </div>
                                </div>
                            @endif
                            <!--/span-->
                            @if (in_array('employees-join date',$modules))
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="required">@lang('modules.employees.joiningDate')</label>
                                        <input type="text" name="joining_date" id="joining_date" @if($employeeDetail) value="{{ $employeeDetail->joining_date->format($global->date_format) }}"
                                            @endif class="form-control">
                                    </div>
                                </div>
                            @endif

                            {{-- <div class="col-md-3">
                                <div class="form-group">
                                    <label>@lang('modules.employees.lastDate')</label>
                                    <input type="text" autocomplete="off" name="last_date" id="end_date" @if($employeeDetail) value="@if($employeeDetail->last_date) {{ $employeeDetail->last_date->format($global->date_format) }} @endif"
                                        @endif class="form-control">
                                </div>
                            </div> --}}

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>@lang('modules.employees.gender')</label>
                                    <select name="gender" id="gender" class="form-control">
                                            <option @if($userDetail->gender == 'male') selected
                                                    @endif value="male">@lang('app.male')</option>
                                            <option @if($userDetail->gender == 'female') selected
                                                    @endif value="female">@lang('app.female')</option>
                                            <option @if($userDetail->gender == 'others') selected
                                                    @endif value="others">@lang('app.others')</option>
                                        </select>
                                </div>
                            </div>

                        </div>
                        <!--/row-->

                        <div class="row">
                            <div class="col-xs-12">
                                <div class="form-group">
                                    <label class="control-label">@lang('app.address')</label>
                                    <textarea name="address" id="address" rows="5" class="form-control">{{ $employeeDetail->address ?? '' }}</textarea>
                                </div>
                            </div>

                        </div>
                        <!--/span-->
                        @if (in_array('employees-skill',$modules))
                            <div class="row">
                                <div class="col-md-12 ">
                                    <div class="form-group">
                                        <label>@lang('app.skills')</label>
                                        <input name='tags' placeholder='@lang('app.skills')' value='{{implode(' , ', $userDetail->skills()) }}'>
                                    </div>
                                </div>
                            </div>
                        @endif
                        <!--/row-->
                        <div class="row">
                            <div class="col-md-6 ">
                                <div class="form-group">
                                    <label class="required">@lang('app.subcompany') 
                                        {{-- <a href="javascript:;" id="subcompany-setting" ><i class="ti-settings text-info"></i></a> --}}
                                    </label>
                                    <select name="subcompany" id="subcompany" class="form-control">
                                        @forelse($subcompanys as $subcompany)
                                            <option @if(isset($employeeDetail->sub_company_id) && $employeeDetail->sub_company_id == $subcompany->id) selected @endif value="{{ $subcompany->id }}">{{ $subcompany->name }}</option>
                                        @empty
                                            <option value="">@lang('messages.noRecordFound')</option>
                                        @endforelse()
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="required">@lang('app.cabang') 
                                        {{-- <a href="javascript:;" id="cabang-setting" ><i class="ti-settings text-info"></i></a> --}}
                                    </label>
                                    <select name="cabang" id="cabang" class="form-control">
                                        @forelse($cabangs as $cabang)
                                            <option @if(isset($employeeDetail->cabang_id) && $employeeDetail->cabang_id == $cabang->id) selected @endif value="{{ $cabang->id }}">{{ $cabang->name }}</option>
                                        @empty
                                            <option value="">@lang('messages.noRecordFound')</option>
                                        @endforelse()
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 ">
                                <div class="form-group">
                                    <label class="required">@lang('app.wilayah') 
                                        {{-- <a href="javascript:;" id="wilayah-setting" ><i class="ti-settings text-info"></i></a> --}}
                                    </label>
                                    <select name="wilayah" id="wilayah" class="form-control">
                                        @forelse($wilayahs as $wilayah)
                                            <option @if(isset($employeeDetail->wilayah_id) && $employeeDetail->wilayah_id == $wilayah->id) selected @endif value="{{ $wilayah->id }}">{{ $wilayah->name }}</option>
                                        @empty
                                            <option value="">@lang('messages.noRecordFound')</option>
                                        @endforelse()
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="required">@lang('app.department') 
                                        {{-- <a href="javascript:;" id="department-setting" ><i class="ti-settings text-info"></i></a> --}}
                                    </label>
                                    <select name="department" id="department" class="form-control">
                                        <option value="">--</option>
                                        @foreach($teams as $team)
                                            <?php
                                                $childs = \App\Team::where('parent_id',$team->id)->get();
                                            ?>
                                            <option @if(isset($employeeDetail->department_id) && $employeeDetail->department_id == $team->id) selected @endif value="{{ $team->id }}">{{ $team->team_name }}</option>
                                            @if (count($childs)>0)
                                                @foreach ($childs as $child)
                                                    <option @if(isset($employeeDetail->department_id) && $employeeDetail->department_id == $child->id) selected @endif value="{{ $child->id }}">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ $child->team_name }}</option>
                                                @endforeach
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 ">
                                <div class="form-group">
                                    <label class="required">@lang('app.designation') 
                                        {{-- <a href="javascript:;" id="designation-setting" ><i class="ti-settings text-info"></i></a> --}}
                                    </label>
                                    <select name="designation" id="designation" class="form-control">
                                        @forelse($designations as $designation)
                                            <option @if(isset($employeeDetail->designation_id) && $employeeDetail->designation_id == $designation->id) selected @endif value="{{ $designation->id }}">{{ $designation->name }}</option>
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
                                        @lang('modules.employees.no') <input type="checkbox" class="js-switch assign-role-permission" data-size="small" data-color="#00c292" id="karyawan_khusus" name="karyawan_khusus" {{isset($additional_field->karyawan_khusus)?($additional_field->karyawan_khusus=='1'?'checked':''):''}}/> @lang('modules.employees.yes')
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
                                            <option value="{{$cluster_working_hour->id}}" {{$employeeDetail->cluster_working_hour_id==$cluster_working_hour->id?'selected':''}}>{{$cluster_working_hour->name}} ({{$cluster_working_hour->type}})</option>
                                        @endforeach
                                    </select>
                                </div>
                              
                            </div>
                        </div>
                        <div class="row">
                            <!--/span-->

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>@lang('app.mobile')</label>
                                    <input type="tel" name="mobile" id="mobile" class="form-control" value="{{ $userDetail->mobile }}" autocomplete="nope">
                                </div>
                            </div>
                            @if (in_array('employees-hourly rate',$modules))
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>@lang('modules.employees.hourlyRate')</label>
                                        <input type="text" name="hourly_rate" id="hourly_rate" class="form-control" value="{{ $employeeDetail->hourly_rate ?? '' }}">
                                    </div>
                                </div>
                            @endif
                            <!--/span-->

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>@lang('app.status')</label>
                                    <select name="status" id="status" class="form-control">
                                            <option @if($userDetail->status == 'active') selected
                                                    @endif value="active">@lang('app.active')</option>
                                            <option @if($userDetail->status == 'deactive') selected
                                                    @endif value="deactive">@lang('app.deactive')</option>
                                        </select>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>@lang('app.login')</label>
                                    <select name="login" id="login" class="form-control">
                                        <option @if($userDetail->login == 'enable') selected @endif value="enable">@lang('app.enable')</option>
                                        <option @if($userDetail->login == 'disable') selected @endif value="disable">@lang('app.disable')</option>
                                    </select>
                                </div>
                            </div>

                        </div>
                        {{-- <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <div class="input-group bootstrap-timepicker timepicker">
                                        <label>@lang('modules.attendance.officeStartTime')</label>
                                        <input type="text" name="office_start_time" id="office_start_time" class="form-control" value="{{$employeeDetail->office_start_time}}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <div class="input-group bootstrap-timepicker timepicker">
                                        <label>@lang('modules.attendance.officeEndTime')</label>
                                        <input type="text" name="office_end_time" id="office_end_time"
                                               class="form-control" value="{{$employeeDetail->office_end_time}}">
                                    </div>
                                </div>
                            </div>
                        </div> --}}
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="latitude">Latitude</label>
                                    <input type="text" class="form-control" id="latitude" name="latitude" placeholder="Latitude" value="{{$employeeDetail->latitude}}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="longitude">Longitude</label>
                                    <input type="text" class="form-control" id="longitude" name="longitude" placeholder="Longitude" value="{{$employeeDetail->longitude}}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label for="">@lang('modules.employees.activateUserEditLatLong')</label>
                                <div class="switchery-demo">
                                    @lang('modules.employees.no') <input type="checkbox" class="js-switch assign-role-permission" data-size="small" data-color="#00c292" id="edit_lat_long" name="edit_lat_long" {{isset($additional_field->edit_lat_long)?($additional_field->edit_lat_long=='1'?'checked':''):''}}/> @lang('modules.employees.yes')
                                </div>
                            </div>
                        </div><br>
                        <label> <label>@lang('app.menu.permission')</label></label>
                                <div style="border: solid 1px #eee;padding:10px;">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label>@lang('app.subcompany')</label>
                                            <div class="form-group">
                                                @forelse($subcompanys as $subcompany)
                                                    <div class="checkbox checkbox-inline checkbox-info  col-md-2 m-b-10">
                                                        <input id="{{$subcompany->id.'-subcompany-'.$subcompany->name}}" 
                                                        name="subcompany_rule[]" value="subcompany.{{setPermissionEmployee($subcompany->name)}}" 
                                                        type="checkbox" {{setCheckedPermissionEmployee($employeeDetail->permission,'subcompany.'.setPermissionEmployee($subcompany->name))}}>
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
                                                        <input id="{{$wilayah->id.'-wilayah-'.$wilayah->name}}" 
                                                        name="wilayah_rule[]" value="wilayah.{{setPermissionEmployee($wilayah->name)}}" 
                                                        type="checkbox" {{setCheckedPermissionEmployee($employeeDetail->permission,'wilayah.'.setPermissionEmployee($wilayah->name))}}>
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
                                                        <input id="{{$cabang->id.'-cabang-'.$cabang->name}}" 
                                                        name="cabang_rule[]" value="cabang.{{setPermissionEmployee($cabang->name)}}" 
                                                        type="checkbox" {{setCheckedPermissionEmployee($employeeDetail->permission,'cabang.'.setPermissionEmployee($cabang->name))}}>
                                                        <label for="{{$cabang->id.'-cabang-'.$cabang->name}}">{{$cabang->name}}</label>
                                                    </div>
                                                @empty
                                                    @lang('app.noDataFound')
                                                @endforelse()
                                            </div>
                                        </div>
                                    </div><br>
                                </div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>@lang('modules.employees.persetujuan_1')</label>
                                    <select name="persetujuan_satu" id="persetujuan_satu" class="form-control">
                                        <option value="">@lang('modules.employees.choose_employee')</option>
                                        @foreach ($listEmployee as $item)
                                            <option value="{{$item->id}}" {{$approvalLevel[0]==$item->id?'selected':''}}>{{$item->name}}</option>
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
                                            <option value="{{$item->id}}" {{$approvalLevel[1]==$item->id?'selected':''}}>{{$item->name}}</option>
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
                                            <option value="{{$item->id}}" {{$approvalLevel[2]==$item->id?'selected':''}}>{{$item->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        {{-- <div class="row">
                            <div class="col-md-6">
                                <label for="">@lang('modules.employees.aturTugas')</label>
                                <div class="switchery-demo">
                                    @lang('modules.employees.no') <input type="checkbox" class="js-switch assign-role-permission" data-size="small" data-color="#00c292" id="atur_tugas" name="atur_tugas" {{isset($additional_field->manage_task)?($additional_field->manage_task=='1'?'checked':''):''}}/> @lang('modules.employees.yes')
                                </div>
                            </div>
                        </div><br> --}}
                        <div class="row">
                            <div class="col-md-6">
                                <label for="">@lang('modules.employees.options')</label>
                                <div class="switchery-demo">
                                @lang('modules.employees.no') <input type="checkbox" class="js-switch assign-role-permission" data-size="small" data-color="#00c292" id="option_employee" name="option_employee" {{$employeeDetail->option_employee=='1'?'checked':''}}/> @lang('modules.employees.yes')
                                </div>
                            </div>
                        </div><br>
                        {{-- <div class="row">
                            <div class="col-md-6">
                                <label for="">@lang('modules.employees.menambahkanPengumuman')</label>
                                <div class="switchery-demo">
                                    @lang('modules.employees.no') <input type="checkbox" class="js-switch assign-role-permission" data-size="small" data-color="#00c292" id="menambahkan_pengumuman" name="menambahkan_pengumuman" {{isset($additional_field->manage_notice)?($additional_field->manage_notice=='1'?'checked':''):''}}/> @lang('modules.employees.yes')
                                </div>
                            </div>
                        </div><br> --}}
                        {{-- <div class="row">
                            <div class="col-md-6">
                                <label for="">@lang('modules.employees.aturProject')</label>
                                <div class="switchery-demo">
                                    @lang('modules.employees.no') <input type="checkbox" class="js-switch assign-role-permission" data-size="small" data-color="#00c292" id="atur_project" name="atur_project" {{isset($additional_field->manage_project)?($additional_field->manage_project=='1'?'checked':''):''}}/> @lang('modules.employees.yes')
                                </div>
                            </div>
                        </div><br> --}}
                        <div class="row">
                            <div class="col-md-6">
                                <label for="">Menerima / Menolak Laporan Tugas</label>
                                <div class="switchery-demo">
                                    @lang('modules.employees.no') <input type="checkbox" class="js-switch assign-role-permission" data-size="small" data-color="#00c292" id="report_task" name="report_task" {{isset($additional_field->report_task)?($additional_field->report_task=='1'?'checked':''):''}}/> @lang('modules.employees.yes')
                                </div>
                            </div>
                        </div><br>
                        {{-- <div class="row">
                            <div class="col-md-6">
                                <label for="">@lang('modules.employees.reportTask')</label>
                                <div class="switchery-demo">
                                    @lang('modules.employees.no') <input type="checkbox" class="js-switch assign-role-permission" data-size="small" data-color="#00c292" id="report_task" name="report_task" {{isset($additional_field->report_task)?($additional_field->report_task=='1'?'checked':''):''}}/> @lang('modules.employees.yes')
                                </div>
                            </div>
                        </div><br> --}}
                        
                        <div class="col-sm-12">
                            {{-- <label class="control-label col-md-12 p-l-0">@lang('modules.attendance.officeOpenDays')</label> --}}
                            <div class="row">
                                <hr>
                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="">List Tugas</label>
                                            <div class="switchery-demo">
                                                @lang('modules.employees.no') <input type="checkbox" class="js-switch assign-role-permission" data-size="small" data-color="#00c292" id="list_tugas" name="list_tugas" {{isset($additional_field->list_tugas)?($additional_field->list_tugas=='1'?'checked':''):''}}/> @lang('modules.employees.yes')
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="">Create Tugas</label>
                                            <div class="switchery-demo">
                                                @lang('modules.employees.no') <input type="checkbox" class="js-switch assign-role-permission" data-size="small" data-color="#00c292" id="create_tugas" name="create_tugas" {{isset($additional_field->create_tugas)?($additional_field->create_tugas=='1'?'checked':''):''}}/> @lang('modules.employees.yes')
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="">Edit Tugas</label>
                                            <div class="switchery-demo">
                                                @lang('modules.employees.no') <input type="checkbox" class="js-switch assign-role-permission" data-size="small" data-color="#00c292" id="edit_tugas" name="edit_tugas" {{isset($additional_field->edit_tugas)?($additional_field->edit_tugas=='1'?'checked':''):''}}/> @lang('modules.employees.yes')
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="">Hapus Tugas</label>
                                            <div class="switchery-demo">
                                                @lang('modules.employees.no') <input type="checkbox" class="js-switch assign-role-permission" data-size="small" data-color="#00c292" id="delete_tugas" name="delete_tugas" {{isset($additional_field->delete_tugas)?($additional_field->delete_tugas=='1'?'checked':''):''}}/> @lang('modules.employees.yes')
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
                                                @lang('modules.employees.no') <input type="checkbox" class="js-switch assign-role-permission" data-size="small" data-color="#00c292" id="list_proyek" name="list_proyek" {{isset($additional_field->list_proyek)?($additional_field->list_proyek=='1'?'checked':''):''}}/> @lang('modules.employees.yes')
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="">Create Proyek</label>
                                            <div class="switchery-demo">
                                                @lang('modules.employees.no') <input type="checkbox" class="js-switch assign-role-permission" data-size="small" data-color="#00c292" id="create_proyek" name="create_proyek" {{isset($additional_field->create_proyek)?($additional_field->create_proyek=='1'?'checked':''):''}}/> @lang('modules.employees.yes')
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="">Edit Proyek</label>
                                            <div class="switchery-demo">
                                                @lang('modules.employees.no') <input type="checkbox" class="js-switch assign-role-permission" data-size="small" data-color="#00c292" id="edit_proyek" name="edit_proyek" {{isset($additional_field->edit_proyek)?($additional_field->edit_proyek=='1'?'checked':''):''}}/> @lang('modules.employees.yes')
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="">Hapus Proyek</label>
                                            <div class="switchery-demo">
                                                @lang('modules.employees.no') <input type="checkbox" class="js-switch assign-role-permission" data-size="small" data-color="#00c292" id="delete_proyek" name="delete_proyek" {{isset($additional_field->delete_proyek)?($additional_field->delete_proyek=='1'?'checked':''):''}}/> @lang('modules.employees.yes')
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
                                                @lang('modules.employees.no') <input type="checkbox" class="js-switch assign-role-permission" data-size="small" data-color="#00c292" id="list_pengumuman" name="list_pengumuman" {{isset($additional_field->list_pengumuman)?($additional_field->list_pengumuman=='1'?'checked':''):''}}/> @lang('modules.employees.yes')
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="">Create Pengumuman</label>
                                            <div class="switchery-demo">
                                                @lang('modules.employees.no') <input type="checkbox" class="js-switch assign-role-permission" data-size="small" data-color="#00c292" id="create_pengumuman" name="create_pengumuman" {{isset($additional_field->create_pengumuman)?($additional_field->create_pengumuman=='1'?'checked':''):''}}/> @lang('modules.employees.yes')
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="">Edit Pengumuman</label>
                                            <div class="switchery-demo">
                                                @lang('modules.employees.no') <input type="checkbox" class="js-switch assign-role-permission" data-size="small" data-color="#00c292" id="edit_pengumuman" name="edit_pengumuman" {{isset($additional_field->edit_pengumuman)?($additional_field->edit_pengumuman=='1'?'checked':''):''}}/> @lang('modules.employees.yes')
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="">Hapus Pengumuman</label>
                                            <div class="switchery-demo">
                                                @lang('modules.employees.no') <input type="checkbox" class="js-switch assign-role-permission" data-size="small" data-color="#00c292" id="delete_pengumuman" name="delete_pengumuman" {{isset($additional_field->delete_pengumuman)?($additional_field->delete_pengumuman=='1'?'checked':''):''}}/> @lang('modules.employees.yes')
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="">List Masalah</label>
                                        <div class="switchery-demo">
                                            @lang('modules.employees.no') <input type="checkbox" class="js-switch assign-role-permission" data-size="small" data-color="#00c292" id="list_ticket" name="list_ticket" {{isset($additional_field->list_ticket)?($additional_field->list_ticket=='1'?'checked':''):''}}/> @lang('modules.employees.yes')
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="">Create Masalah</label>
                                        <div class="switchery-demo">
                                            @lang('modules.employees.no') <input type="checkbox" class="js-switch assign-role-permission" data-size="small" data-color="#00c292" id="create_ticket" name="create_ticket" {{isset($additional_field->create_ticket)?($additional_field->create_ticket=='1'?'checked':''):''}}/> @lang('modules.employees.yes')
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="">Edit Masalah</label>
                                        <div class="switchery-demo">
                                            @lang('modules.employees.no') <input type="checkbox" class="js-switch assign-role-permission" data-size="small" data-color="#00c292" id="edit_ticket" name="edit_ticket" {{isset($additional_field->edit_ticket)?($additional_field->edit_ticket=='1'?'checked':''):''}}/> @lang('modules.employees.yes')
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="">Hapus Masalah</label>
                                        <div class="switchery-demo">
                                            @lang('modules.employees.no') <input type="checkbox" class="js-switch assign-role-permission" data-size="small" data-color="#00c292" id="delete_ticket" name="delete_ticket" {{isset($additional_field->delete_ticket)?($additional_field->delete_ticket=='1'?'checked':''):''}}/> @lang('modules.employees.yes')
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="">Balas Masalah</label>
                                        <div class="switchery-demo">
                                            @lang('modules.employees.no') <input type="checkbox" class="js-switch assign-role-permission" data-size="small" data-color="#00c292" id="reply_ticket" name="reply_ticket" {{isset($additional_field->reply_ticket)?($additional_field->reply_ticket=='1'?'checked':''):''}}/> @lang('modules.employees.yes')
                                        </div>
                                    </div>
                                </div>
                            </div>
                          		<?php /*
                                <div class="form-group">
                                    <label class="required">Kapal
                                        {{-- <a href="javascript:;" id="office-setting" ><i class="ti-settings text-info"></i></a> --}}
                                    </label>
                                    <select name="office_id" id="office_id" class="form-control">
                                        @forelse($office as $off)
                                            <option @if(isset($employeeDetail->office_id) && $employeeDetail->office_id == $off->id) selected @endif value="{{ $off->id }}">{{ $off->name }}</option>
                                        @empty
                                            <option value="">@lang('messages.noRecordFound')</option>
                                        @endforelse()
                                    </select>
                                </div>
                                */ ?>
                              
                            <div class="col-md-6 ">
                                <div class="form-group">
                                    <label for="">ABK Kapal</label>
                                    <div class="switchery-demo">
                                        @lang('modules.employees.no') <input type="checkbox" class="js-switch assign-role-permission" data-size="small" data-color="#00c292" id="is_abk" name="is_abk" {{isset($employeeDetail->is_abk)?($employeeDetail->is_abk=='1'?'checked':''):''}}/> @lang('modules.employees.yes')
                                    </div>
                                </div>
                            </div>
                              
                            <div class="col-md-6 ">
                                <div class="form-group">
                                    <label for="">HRD Kapal</label>
                                    <div class="switchery-demo">
                                        @lang('modules.employees.no') <input type="checkbox" class="js-switch assign-role-permission" data-size="small" data-color="#00c292" id="is_hrd_kapal" name="is_hrd_kapal" {{isset($employeeDetail->is_hrd_kapal)?($employeeDetail->is_hrd_kapal=='1'?'checked':''):''}}/> @lang('modules.employees.yes')
                                    </div>
                                </div>
                            </div>
                              
                            <div class="col-md-6 ">
                                <div class="form-group">
                                    <label for="">PC</label>
                                    <div class="switchery-demo">
                                        @lang('modules.employees.no') <input type="checkbox" class="js-switch assign-role-permission" data-size="small" data-color="#00c292" id="is_pc" name="is_pc" {{isset($employeeDetail->is_pc)?($employeeDetail->is_pc=='1'?'checked':''):''}}/> @lang('modules.employees.yes')
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
                                            <img src="{{ $userDetail->image_url }}" alt="" />
                                        </div>
                                        <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px;"></div>
                                        <div>
                                            <span class="btn btn-info btn-file">
                                    <span class="fileinput-new"> @lang('app.selectImage') </span>
                                            <span class="fileinput-exists"> @lang('app.change') </span>
                                            <input type="file" name="image" id="image"> </span>
                                            <a href="javascript:;" class="btn btn-danger fileinput-exists" data-dismiss="fileinput"> @lang('app.remove') </a>
                                        </div>
                                    </div>
                                </div>

                            </div>

                        </div>
                        <!--/span-->

                        <div class="row">
                            @if(isset($fields)) @foreach($fields as $field)
                            <div class="col-md-6">
                                <label>{{ ucfirst($field->label) }}</label>
                                <div class="form-group">
                                    @if( $field->type == 'text')
                                    <input type="text" name="custom_fields_data[{{$field->name.'_'.$field->id}}]" class="form-control" placeholder="{{$field->label}}"
                                        value="{{$employeeDetail->custom_fields_data['field_'.$field->id] ?? ''}}">                                    @elseif($field->type == 'password')
                                    <input type="password" name="custom_fields_data[{{$field->name.'_'.$field->id}}]" class="form-control" placeholder="{{$field->label}}"
                                        value="{{$employeeDetail->custom_fields_data['field_'.$field->id] ?? ''}}">                                    @elseif($field->type == 'number')
                                    <input type="number" name="custom_fields_data[{{$field->name.'_'.$field->id}}]" class="form-control" placeholder="{{$field->label}}"
                                        value="{{$employeeDetail->custom_fields_data['field_'.$field->id] ?? ''}}">                                    @elseif($field->type == 'textarea')
                                    <textarea name="custom_fields_data[{{$field->name.'_'.$field->id}}]" class="form-control" id="{{$field->name}}" cols="3">{{$employeeDetail->custom_fields_data['field_'.$field->id] ?? ''}}</textarea>                                    @elseif($field->type == 'radio')
                                    <div class="radio-list">
                                        @foreach($field->values as $key=>$value)
                                        <label class="radio-inline @if($key == 0) p-0 @endif">
                                                                <div class="radio radio-info">
                                                                    <input type="radio"
                                                                           name="custom_fields_data[{{$field->name.'_'.$field->id}}]"
                                                                           id="optionsRadios{{$key.$field->id}}"
                                                                           value="{{$value}}"
                                                                           @if(isset($employeeDetail) && $employeeDetail->custom_fields_data['field_'.$field->id] == $value) checked
                                                                           @elseif($key==0) checked @endif>>
                                                                    <label for="optionsRadios{{$key.$field->id}}">{{$value}}</label>
                                    </div>
                                    </label>
                                    @endforeach
                                </div>
                                @elseif($field->type == 'select') {!! Form::select('custom_fields_data['.$field->name.'_'.$field->id.']', $field->values,
                                isset($employeeDetail)?$employeeDetail->custom_fields_data['field_'.$field->id]:'',['class'
                                => 'form-control gender']) !!} @elseif($field->type == 'checkbox')
                                <div class="mt-checkbox-inline">
                                    @foreach($field->values as $key => $value)
                                    <label class="mt-checkbox mt-checkbox-outline">
                                                                <input name="custom_fields_data[{{$field->name.'_'.$field->id}}][]"
                                                                       type="checkbox" value="{{$key}}"> {{$value}}
                                                                <span></span>
                                                            </label> @endforeach
                                </div>
                                @elseif($field->type == 'date')
                                <input type="text" class="form-control date-picker" size="16" name="custom_fields_data[{{$field->name.'_'.$field->id}}]"
                                    value="{{ ($employeeDetail->custom_fields_data['field_'.$field->id] != '') ? \Carbon\Carbon::createFromFormat('m/d/Y', $employeeDetail->custom_fields_data['field_'.$field->id])->format('m/d/Y') : \Carbon\Carbon::now()->format('m/d/Y')}}">                                @endif
                                <div class="form-control-focus"></div>
                                <span class="help-block"></span>

                            </div>
                        </div>
                        @endforeach @endif

                    </div>



                </div>
                <div class="form-actions">
                    <button type="submit" id="save-form" class="btn btn-success"><i
                                        class="fa fa-check"></i> @lang('app.update')</button>
                    <a href="{{ route('member.abk.index') }}" class="btn btn-default">@lang('app.back')</a>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
</div>
<!-- .row -->

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
    $("#joining_date, .date-picker,  #end_date").datepicker({
            todayHighlight: true,
            autoclose: true,
            weekStart:'{{ $global->week_start }}',
            format: '{{ $global->date_picker_format }}',
        });

        $('#save-form').click(function () {
            $.easyAjax({
                url: '{{route('member.abk.update', [$userDetail->id])}}',
                container: '#updateEmployee',
                type: "POST",
                redirect: true,
                file: (document.getElementById("image").files.length == 0) ? false : true,
                data: $('#updateEmployee').serialize()
            })
        });

</script>

@endpush
