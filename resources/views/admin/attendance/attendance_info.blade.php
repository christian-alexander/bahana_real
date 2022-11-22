<link rel="stylesheet" href="{{ asset('plugins/bower_components/timepicker/bootstrap-timepicker.min.css') }}">
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4 class="modal-title" ><i class="icon-clock"></i> @lang('app.menu.attendance') @lang('app.details') </h4>
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-md-6">
            <div class="card punch-status">
                <div class="white-box">
                    <h4>@lang('app.menu.attendance') <small class="text-muted">{{ $startTime->format($global->date_format) }}</small></h4>
                    <div class="punch-det">
                        <h6>@lang('modules.attendance.clock_in')</h6>
                        <p>{{ $startTime->format($global->time_format) }}</p>
                    </div>
                    <div class="punch-info">
                        <div class="punch-hours">
                            <span>{{ $totalTime }} hrs</span>
                        </div>
                    </div>
                    <div class="punch-det">
                        <h6>@lang('modules.attendance.clock_out')</h6>
                        <p>{{ $endTime->copy()->format($global->time_format) }} {{$attendance->cron_clock_out==1?'(Absen Pulang By System)':''}}
                            @if (isset($notClockedOut))
                                (@lang('modules.attendance.notClockOut'))
                            @endif
                        </p>
                    </div>
                    @if (count($pertanyaan)>0)
                        @foreach ($pertanyaan as $item)
                            <div class="punch-det">
                                <h6>{{$item->pertanyaan}}</h6>
                                <p>{{ $item->jawaban }}</p>
                                {{-- <p>{{ $item->jawaban }} <span>&#8451;</span></p> --}}
                            </div>
                        @endforeach
                    @endif
                    @if (isset($leave) && !empty($leave))
                    <hr>
                        <div class="punch-info">
                            Tipe: {{$leave->display_name}} | {{$leave->child->alasan_ijin}}<br>
                            Alasan: {{$leave->reason}} <b>{{$leave->approved_by=='system'?'(SYSTEM)':''}}</b>
                        </div>
                    @elseif($pulang_awal_system)
                    <hr>
                        <div class="punch-info">
                            Tipe: Ijin | Pulang Awal<br>
                            Alasan: - <b>(SYSTEM-DATA)</b>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card recent-activity">
                <div class="white-box">
                    <h5 class="card-title">@lang('modules.employees.activity')</h5>

                    @foreach ($attendanceActivity->reverse() as $item)
                        <div class="row res-activity-box" id="timelogBox{{ $item->aId }}">
                            <ul class="res-activity-list col-md-12">
                                <li>
                                    <p class="mb-0">@lang('modules.attendance.clock_in')</p>
                                    <p class="res-activity-time">
                                        <i class="fa fa-clock-o"></i>
                                        {{ $item->clock_in_after_timezone->format($global->date_format) }} {{ $item->clock_in_after_timezone->format($global->time_format) }}.
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="input-group form-control bootstrap-timepicker timepicker">
                                                    <input type="text" name="time" class="form-control type_time" value="{{ $startTime->format($global->time_format) }}">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <button type="button" id="update-data" data-type="clock_in" data-attendance-id="{{$attendance->id}}" class="btn btn-sm btn-success waves-effect waves-light m-r-10">
                                                    Update
                                                </button>
                                            </div>
                                        </div>
                                    </p>
                                    @if ($flag_late)
                                        <span style="font-weight: bold;color: red">(Terlambat)</span>
                                    @endif
                                </li>
                                <li>
                                    <p class="mb-0">@lang('modules.attendance.clock_out')</p>
                                    <p class="res-activity-time">
                                        <i class="fa fa-clock-o"></i>
                                        @if (!is_null($item->clock_out_after_timezone))
                                            {{ $item->clock_out_after_timezone->format($global->date_format) }} {{ $item->clock_out_after_timezone->format($global->time_format) }}.
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <input type="hidden" name="type" value="clock_out">
                                                    <div class="input-group form-control bootstrap-timepicker timepicker">
                                                        <input type="text" name="time" class="form-control type_time" value="{{ $endTime->format($global->time_format) }}">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <button type="button" id="update-data" data-type="clock_out" data-attendance-id="{{$attendance->id}}" class="btn btn-sm btn-success waves-effect waves-light m-r-10">
                                                        Update
                                                    </button>
                                                </div>
                                            </div>
                                        @else
                                            @lang('modules.attendance.notClockOut')
                                        @endif
                                    </p>
                                </li>
                            </ul>
                            

                            {{-- <div class="col-md-3">
                                <a href="javascript:;" onclick="editAttendance({{ $item->aId }})" style="display: inline-block;" id="attendance-edit" data-attendance-id="{{ $item->aId }}" ><label class="label label-info"><i class="fa fa-pencil"></i> </label></a>
                                <a href="javascript:;" onclick="deleteAttendance({{ $item->aId }})" style="display: inline-block;" id="attendance-edit" data-attendance-id="{{ $item->aId }}" ><label class="label label-danger"><i class="fa fa-times"></i></label></a>
                            </div> --}}
                        </div>
                    @endforeach
                    

                    <br>
                    <h5>Cluster info</h5>
                    Jam masuk cluster : {{$office_start_time}}<br>
                    Jam pulang cluster : {{$office_end_time}}
                    <br>
                    <hr>

                    <h5>
                        <?php
                            if($attendance->clock_out_from == 'WFH'){
                                echo 'WFH';
                            }else if($attendance->clock_out_from == 'GPS'){
                                echo 'GPS';
                            }else if($attendance->clock_out_from != 'WFH' && $attendance->clock_out_from != 'GPS'){
                                echo 'WFO';
                            }
                        ?>
                    </h5>
                    <p>Clock in from: {{ $attendance->working_from }}</p>
                    <p>Clock out from: {{ $attendance->clock_out_from }}</p>

                    <hr>
                    <h5>Clock in image</h5>
                    @if (isset($attendance->clock_in_image))
                        <img src="{{asset_url_local_s3('attendance/'.$attendance->clock_in_image)}}" alt="" style="width:150px;">
                    @else
                        (No image)
                    @endif
                    <br>
                    <h5>Clock out image</h5>
                    @if (isset($attendance->clock_out_image))
                        <img src="{{asset_url_local_s3('attendance/'.$attendance->clock_out_image)}}" alt="" style="width:150px;">
                    @else
                        (No image)
                    @endif
                    <br>
                    <h5>Clock in location</h5>
                    @if (isset($attendance->clock_in_latitude) && isset($attendance->clock_in_longitude))
                        <iframe 
                            width="300" 
                            height="170" 
                            frameborder="0" 
                            scrolling="no" 
                            marginheight="0" 
                            marginwidth="0" 
                            src="https://maps.google.com/maps?q={{$attendance->clock_in_latitude}},{{$attendance->clock_in_longitude}}&hl=id&z=14&amp;output=embed"
                        >
                        </iframe>
                    @else
                        (No location)
                    @endif
                    <br>
                    <h5>Clock out location</h5>
                    @if (isset($attendance->clock_out_latitude) && isset($attendance->clock_out_longitude))
                    <iframe 
                    width="300" 
                    height="170" 
                    frameborder="0" 
                    scrolling="no" 
                    marginheight="0" 
                    marginwidth="0" 
                    src="https://maps.google.com/maps?q={{$attendance->clock_out_latitude}},{{$attendance->clock_out_longitude}}&hl=id&z=14&amp;output=embed"
                >
                </iframe>
                    @else
                        (No location)
                    @endif
                </div>
            </div>
        </div>
        {{-- <div class="com-md-12">
            <div class="white-box">
                {!! Form::open(['id'=>'createOffice','class'=>'ajax-form','method'=>'POST']) !!}
                <div class="form-group">
                    <label for="clock_in">Clock In</label>
                    <div class="input-group form-control bootstrap-timepicker timepicker">
                        <input type="text" name="clock_in" class="form-control type_time" value="{{ $startTime->format($global->time_format) }}">
                    </div>
                </div>
                @if (!isset($notClockedOut))
                    <div class="form-group">
                        <label for="clock_on">Clock On</label>
                        <div class="input-group form-control bootstrap-timepicker timepicker">
                            <input type="text" name="clock_on" class="form-control type_time" value="{{ $endTime->copy()->format($global->time_format) }}">
                        </div>
                    </div>
                @endif
                <button type="submit" id="save-form" class="btn btn-success waves-effect waves-light m-r-10">
                    @lang('app.save')
                </button>
                <a href="{{route('admin.office.index')}}" class="btn btn-default waves-effect waves-light">@lang('app.back')</a>
                {!! Form::close() !!}
            </div>
        </div> --}}
    </div>

</div>
<script src="{{ asset('plugins/bower_components/timepicker/bootstrap-timepicker.min.js') }}"></script>
<script>
    $('.type_time, #start_hour, #end_hour').timepicker({
        @if($global->time_format == 'H:i')
        showMeridian: false
        @endif
    });
    $('body').on('click', '#update-data', function(){
            var type = $(this).data('type');
            var attendance_id = $(this).data('attendance-id');
            var time = $(this).parent().closest('.row').find("input[name=time]").val();
            swal({
                title: "Are you sure?",
                text: "You will not be able to revert this action!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, update it!",
                cancelButtonText: "No, cancel please!",
                closeOnConfirm: true,
                closeOnCancel: true
            }, function(isConfirm){
                if (isConfirm) {

                    var url = "{{ route('admin.attendances.updateClockInOrClockOut') }}";

                    var token = "{{ csrf_token() }}";

                    $.easyAjax({
                        type: 'POST',
                        url: url,
                        data: {
                            '_token': token,
                            'type': type,
                            'attendance_id': attendance_id,
                            'time': time,
                            },
                        success: function (response) {
                            if (response.status == "success") {
                                // get selector to change value
                                var action = swal("Updated!", response.status, "success");
                                $('#projectTimerModal').modal('toggle');
                            }
                        }
                    });
                }
            });
        });

</script>