<style>
    .table-content-class {
        overflow-y: hidden;    
        overflow-x: scroll;    
    }
</style>
<div class="white-box">
    <div class="table-responsive tableFixHead table-content-class">
        <table class="table table-nowrap mb-0" id="custom-datatable">
            <thead >
                <tr>
                    <th>@lang('app.employee')</th>
                    {{-- @for($i =1; $i <= $daysInMonth; $i++)
                        <th>{{ $i }}</th>
                    @endfor --}}
                    @foreach ($dataTillEndDate as $key => $item)
                        {{-- @if ($key != 0) --}}
                        <th style="text-align: center">{{ $key }}</th>
                        {{-- @endif --}}
                    @endforeach
                    <th>@lang('app.total')</th>
                </tr>
            </thead>
            <tbody>
                @foreach($employeeAttendence as $key => $attendance)
                @php
                    $totalPresent = 0;
                @endphp
                <tr>
                    {{-- <td> {{ substr($key, strripos($key,'#')+strlen('#')) }} </td> --}}
                    <td> {!! end($attendance) !!}</td>
                    @foreach($attendance as $key2=>$day)
                        {{-- @if (strpos($day, 'fa fa-check text-success') !== false || $day == 'Absent' || $day == 'Holiday' || $day == 'Ijin' || $day == 'Cuti') --}}
                        @if (strpos($day, 'fa fa-check text-success') !== false || $day == 'Absent' || $day == 'Holiday'|| $day == 'Ijin' || strpos($day, 'fa fa-envelope-o text-danger')!== false || $day == 'Cuti')
                            <td class="text-center">
                                @if($day == 'Absent')
                                    <a href="#" class="edit-attendance" data-attendance-date="{{ $key2 }}" title="Alpha"><i class="fa fa-times text-danger"></i></a>
                                @elseif($day == 'Ijin')
                                    <a href="javascript:;" class="edit-attendance" data-attendance-date="{{ $key2 }}" title="Ijin"><i class="fa fa-envelope-o text-danger"></i></a>
                                @elseif($day == 'Cuti')
                                    <a href="javascript:;" class="edit-attendance" data-attendance-date="{{ $key2 }}" title="Cuti"><i class="fa fa-envelope text-danger"></i></a>
                                @elseif($day == 'Holiday')
                                    <a href="javascript:;" class="edit-attendance" data-attendance-date="{{ $key2 }}" title="Libur"><i class="fa fa-star text-warning"></i></a>
                                @else
                                    @if($day != '-')
                                        @php
                                            $totalPresent = $totalPresent + 1;
                                        @endphp
                                    @endif
                                    {!! $day !!}
                                    
                                @endif
                            </td>
                        @endif
                    @endforeach
                    <td class="text-success">{{ $totalPresent .' / '.(count($attendance)-1) }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>