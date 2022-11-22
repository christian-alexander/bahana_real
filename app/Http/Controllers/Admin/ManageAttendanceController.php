<?php

namespace App\Http\Controllers\Admin;

use App\Attendance;
use App\AttendancePertanyaan;
use App\AttendanceSetting;
use App\ClusterWorkingHour;
use App\Designation;
use App\EmployeeDetails;
use App\Exports\AttendanceExport;
use App\Exports\AttendanceLeadtimeExport;
use App\Helper\Reply;
use App\Holiday;
use App\Http\Requests\Attendance\StoreAttendance;
use App\Leave;
use App\LeaveIjin;
use App\Office;
use App\Pertanyaan;
use App\SubCompany;
use App\Team;
use App\TipeCuti;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\RestAPI\Entities\Employee;
use Yajra\DataTables\Facades\DataTables;
use Maatwebsite\Excel\Facades\Excel;
use App\Wilayah;

class ManageAttendanceController extends AdminBaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'app.menu.attendance';
        $this->pageIcon = 'icon-clock';
        $this->middleware(function ($request, $next) {
            if (!in_array('attendance', $this->user->modules)) {
                abort(403);
            }
            return $next($request);
        });


        // Getting Attendance setting data
        $this->attendanceSettings = AttendanceSetting::first();

        //Getting Maximum Check-ins in a day
        $this->maxAttandenceInDay = $this->attendanceSettings->clockin_in_day;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $openDays = json_decode($this->attendanceSettings->office_open_days);
        $this->startDate = Carbon::today()->timezone($this->global->timezone)->startOfMonth();
        $this->endDate = Carbon::now()->timezone($this->global->timezone);
        $this->employees = User::allEmployees();
        $this->userId = User::first()->id;

        $this->totalWorkingDays = $this->startDate->diffInDaysFiltered(function (Carbon $date) use ($openDays) {
            foreach ($openDays as $day) {
                if ($date->dayOfWeek == $day) {
                    return $date;
                }
            }
        }, $this->endDate);
        $this->daysPresent = Attendance::countDaysPresentByUser($this->startDate, $this->endDate, $this->userId);
        $this->daysLate = Attendance::countDaysLateByUser($this->startDate, $this->endDate, $this->userId);
        $this->halfDays = Attendance::countHalfDaysByUser($this->startDate, $this->endDate, $this->userId);
        $this->holidays = Count(Holiday::getHolidayByDates($this->startDate->format('Y-m-d'), $this->endDate->format('Y-m-d')));

        return view('admin.attendance.index', $this->data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.attendance.create', $this->data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreAttendance $request)
    {
        $date = Carbon::createFromFormat($this->global->date_format, $request->date)->format('Y-m-d');
        $clockIn = Carbon::createFromFormat($this->global->time_format, $request->clock_in_time, $this->global->timezone);
        $clockIn->setTimezone('UTC');
        $clockIn = $clockIn->format('H:i:s');
        if ($request->clock_out_time != '') {
            $clockOut = Carbon::createFromFormat($this->global->time_format, $request->clock_out_time, $this->global->timezone);
            $clockOut->setTimezone('UTC');
            $clockOut = $clockOut->format('H:i:s');
            $clockOut = $date . ' ' . $clockOut;
        } else {
            $clockOut = null;
        }

        $attendance = Attendance::where('user_id', $request->user_id)
            ->where(DB::raw('DATE(`clock_in_time`)'), $date)
            ->whereNull('clock_out_time')
            ->first();

        $clockInCount = Attendance::getTotalUserClockIn($date, $request->user_id);

        if (!is_null($attendance)) {
            $attendance->update([
                'user_id' => $request->user_id,
                'clock_in_time' => $date . ' ' . $clockIn,
                'clock_in_ip' => $request->clock_in_ip,
                'clock_out_time' => $clockOut,
                'clock_out_ip' => $request->clock_out_ip,
                'working_from' => $request->working_from,
                'late' => $request->late,
                'half_day' => $request->half_day
            ]);
        } else {

            // Check maximum attendance in a day
            if ($clockInCount < $this->attendanceSettings->clockin_in_day) {
                Attendance::create([
                    'user_id' => $request->user_id,
                    'clock_in_time' => $date . ' ' . $clockIn,
                    'clock_in_ip' => $request->clock_in_ip,
                    'clock_out_time' => $clockOut,
                    'clock_out_ip' => $request->clock_out_ip,
                    'working_from' => $request->working_from,
                    'late' => $request->late,
                    'half_day' => $request->half_day
                ]);
            } else {
                return Reply::error(__('messages.maxColckIn'));
            }
        }

        return Reply::success(__('messages.attendanceSaveSuccess'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $attendance = Attendance::find($id);

        $this->date = $attendance->clock_in_time->format('Y-m-d');
        $this->row =  $attendance;
        $this->clock_in = 1;
        $this->userid = $attendance->user_id;
        $this->total_clock_in  = Attendance::where('user_id', $attendance->user_id)
            ->where(DB::raw('DATE(attendances.clock_in_time)'), '=', $this->date)
            ->whereNull('attendances.clock_out_time')->count();
        $this->type = 'edit';
        return view('admin.attendance.attendance_mark', $this->data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $attendance = Attendance::findOrFail($id);
        $date = Carbon::createFromFormat($this->global->date_format, $request->attendance_date)->format('Y-m-d');

        $clockIn = Carbon::createFromFormat($this->global->time_format, $request->clock_in_time, $this->global->timezone);
        $clockIn->setTimezone('UTC');
        $clockIn = $clockIn->format('H:i:s');
        if ($request->clock_out_time != '') {
            $clockOut = Carbon::createFromFormat($this->global->time_format, $request->clock_out_time, $this->global->timezone);
            $clockOut->setTimezone('UTC');
            $clockOut = $clockOut->format('H:i:s');
            $clockOut = $date . ' ' . $clockOut;
        } else {
            $clockOut = null;
        }

        $attendance->user_id = $request->user_id;
        $attendance->clock_in_time = $date . ' ' . $clockIn;
        $attendance->clock_in_ip = $request->clock_in_ip;
        $attendance->clock_out_time = $clockOut;
        $attendance->clock_out_ip = $request->clock_out_ip;
        $attendance->working_from = $request->working_from;
        $attendance->late = ($request->has('late')) ? 'yes' : 'no';
        $attendance->half_day = ($request->has('half_day')) ? 'yes' : 'no';
        $attendance->save();

        return Reply::success(__('messages.attendanceSaveSuccess'));
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Attendance::destroy($id);
        return Reply::success(__('messages.attendanceDelete'));
    }

    public function data(Request $request)
    {

        $date = Carbon::createFromFormat($this->global->date_format, $request->date)->format('Y-m-d');
        $attendances = Attendance::attendanceByDate($date);

        return DataTables::of($attendances)
            ->editColumn('id', function ($row) {
                return view('admin.attendance.attendance_list', ['row' => $row, 'global' => $this->global, 'maxAttandenceInDay' => $this->maxAttandenceInDay])->render();
            })
            ->rawColumns(['id'])
            ->removeColumn('name')
            ->removeColumn('clock_in_time')
            ->removeColumn('clock_out_time')
            ->removeColumn('image')
            ->removeColumn('attendance_id')
            ->removeColumn('working_from')
            ->removeColumn('late')
            ->removeColumn('half_day')
            ->removeColumn('clock_in_ip')
            ->removeColumn('designation_name')
            ->removeColumn('total_clock_in')
            ->removeColumn('clock_in')
            ->make();
    }

    public function refreshCount(Request $request, $startDate = null, $endDate = null, $userId = null)
    {

        $openDays = json_decode($this->attendanceSettings->office_open_days);
        // $startDate = Carbon::createFromFormat('!Y-m-d', $startDate);
        // $endDate = Carbon::createFromFormat('!Y-m-d', $endDate)->addDay(1); //addDay(1) is hack to include end date
        $startDate = Carbon::createFromFormat($this->global->date_format, $request->startDate);
        $endDate = Carbon::createFromFormat($this->global->date_format, $request->endDate)->addDay(1); //addDay(1) is hack to include end date
        $userId = $request->userId;

        $totalWorkingDays = $startDate->diffInDaysFiltered(function (Carbon $date) use ($openDays) {
            foreach ($openDays as $day) {
                if ($date->dayOfWeek == $day) {
                    return $date;
                }
            }
        }, $endDate);
        $daysPresent = Attendance::countDaysPresentByUser($startDate, $endDate, $userId);
        $daysLate = Attendance::countDaysLateByUser($startDate, $endDate, $userId);
        $halfDays = Attendance::countHalfDaysByUser($startDate, $endDate, $userId);
        $daysAbsent = (($totalWorkingDays - $daysPresent) < 0) ? '0' : ($totalWorkingDays - $daysPresent);
        $holidays = Count(Holiday::getHolidayByDates($startDate->format('Y-m-d'), $endDate->format('Y-m-d')));

        return Reply::dataOnly(['daysPresent' => $daysPresent, 'daysLate' => $daysLate, 'halfDays' => $halfDays, 'totalWorkingDays' => $totalWorkingDays, 'absentDays' => $daysAbsent, 'holidays' => $holidays]);
    }

    public function employeeData(Request $request, $startDate = null, $endDate = null, $userId = null)
    {
        $ant = []; // Array For attendance Data indexed by similar date
        $dateWiseData = []; // Array For Combine Data

        $startDate = Carbon::createFromFormat($this->global->date_format, $request->startDate)->startOfDay();
        $endDate = Carbon::createFromFormat($this->global->date_format, $request->endDate)->endOfDay()->addDay(1);

        $attendances = Attendance::userAttendanceByDate($startDate, $endDate, $userId); // Getting Attendance Data
        $holidays = Holiday::getHolidayByDates($startDate, $endDate); // Getting Holiday Data

        // Getting Leaves Data
        $leavesDates = Leave::where('user_id', $userId)
            ->where('leave_date', '>=', $startDate)
            ->where('leave_date', '<=', $endDate)
            ->where('status', 'approved')
            ->select('leave_date', 'reason')
            ->get()->keyBy('date')->toArray();

        $holidayData = $holidays->keyBy('holiday_date');
        $holidayArray = $holidayData->toArray();

        // Set Date as index for same date clock-ins
        foreach ($attendances as $attand) {
            $ant[$attand->clock_in_date][] = $attand; // Set attendance Data indexed by similar date
        }

        $endDate = Carbon::createFromFormat($this->global->date_format, $request->endDate)->timezone($this->global->timezone);
        $startDate = Carbon::createFromFormat($this->global->date_format, $request->startDate)->timezone($this->global->timezone)->subDay();

        // Set All Data in a single Array
        for ($date = $endDate; $date->diffInDays($startDate) > 0; $date->subDay()) {

            // Set default array for record
            $dateWiseData[$date->toDateString()] = [
                'holiday' => false,
                'attendance' => false,
                'leave' => false
            ];

            // Set Holiday Data
            if (array_key_exists($date->toDateString(), $holidayArray)) {
                $dateWiseData[$date->toDateString()]['holiday'] = $holidayData[$date->toDateString()];
            }

            // Set Attendance Data
            if (array_key_exists($date->toDateString(), $ant)) {
                $dateWiseData[$date->toDateString()]['attendance'] = $ant[$date->toDateString()];
            }

            // Set Leave Data
            if (array_key_exists($date->toDateString(), $leavesDates)) {
                $dateWiseData[$date->toDateString()]['leave'] = $leavesDates[$date->toDateString()];
            }
        }

        // Getting View data
        $view = view('admin.attendance.user_attendance', ['dateWiseData' => $dateWiseData, 'global' => $this->global])->render();

        return Reply::dataOnly(['status' => 'success', 'data' => $view]);
    }

    public function attendanceByDate()
    {
        return view('admin.attendance.by_date', $this->data);
    }


    public function byDateData(Request $request)
    {
        $date = Carbon::createFromFormat($this->global->date_format, $request->date)->format('Y-m-d');
        $attendances = Attendance::attendanceDate($date)->get();

        return DataTables::of($attendances)
            ->editColumn('id', function ($row) {
                return view('admin.attendance.attendance_date_list', ['row' => $row, 'global' => $this->global])->render();
            })
            ->rawColumns(['id'])
            ->removeColumn('name')
            ->removeColumn('clock_in_time')
            ->removeColumn('clock_out_time')
            ->removeColumn('image')
            ->removeColumn('attendance_id')
            ->removeColumn('working_from')
            ->removeColumn('late')
            ->removeColumn('half_day')
            ->removeColumn('clock_in_ip')
            ->removeColumn('designation_name')
            ->make();
    }

    public function dateAttendanceCount(Request $request)
    {
        $date = Carbon::createFromFormat($this->global->date_format, $request->date)->format('Y-m-d');
        $checkHoliday = Holiday::checkHolidayByDate($date);
        $totalPresent = 0;
        $totalAbsent  = 0;
        $holiday  = 0;
        $holidayReason  = '';
        $totalEmployees = count(User::allEmployees());

        if (!$checkHoliday) {
            $totalPresent = Attendance::where(DB::raw('DATE(`clock_in_time`)'), '=', $date)->count();
            $totalAbsent = ($totalEmployees - $totalPresent);
        } else {
            $holiday = 1;
            $holidayReason = $checkHoliday->occassion;
        }

        return Reply::dataOnly(['status' => 'success', 'totalEmployees' => $totalEmployees, 'totalPresent' => $totalPresent, 'totalAbsent' => $totalAbsent, 'holiday' => $holiday, 'holidayReason' => $holidayReason]);
    }

    public function checkHoliday(Request $request)
    {
        $date = Carbon::createFromFormat($this->global->date_format, $request->date)->format('Y-m-d');
        $checkHoliday = Holiday::checkHolidayByDate($date);
        return Reply::dataOnly(['status' => 'success', 'holiday' => $checkHoliday]);
    }

    // Attendance Detail Show
    public function attendanceDetail(Request $request)
    {

        // Getting Attendance Data By User And Date
        $this->attendances =  Attendance::attedanceByUserAndDate($request->date, $request->userID);
        return view('admin.attendance.attendance-detail', $this->data)->render();
    }

    public function export($startDate = null, $endDate = null, $employee = null)
    {
        //
    }

    public function summary()
    {
        $this->employees = User::allEmployees();
        $now = Carbon::now();
        $this->year = $now->format('Y');
        $this->month = $now->format('m');

        return view('admin.attendance.summary', $this->data);
    }

    public function summaryData(Request $request)
    {
        $employees = User::with(
            ['attendance' => function ($query) use ($request) {
                $query->whereRaw('MONTH(attendances.clock_in_time) = ?', [$request->month])
                    ->whereRaw('YEAR(attendances.clock_in_time) = ?', [$request->year]);
            }]
        )->join('role_user', 'role_user.user_id', '=', 'users.id')
            ->join('roles', 'roles.id', '=', 'role_user.role_id')
            ->select('users.id', 'users.name', 'users.email', 'users.created_at')
            ->where('roles.name', '<>', 'client')->groupBy('users.id');

        if ($request->userId == '0') {
            $employees = $employees->get();
        } else {
            $employees = $employees->where('users.id', $request->userId)->get();
        }

        $this->holidays = Holiday::whereRaw('MONTH(holidays.date) = ?', [$request->month])->whereRaw('YEAR(holidays.date) = ?', [$request->year])->get();

        $final = [];

        $this->daysInMonth = cal_days_in_month(CAL_GREGORIAN, $request->month, $request->year);
        $now = Carbon::now()->timezone($this->global->timezone);
        $requestedDate = Carbon::parse(Carbon::parse('01-' . $request->month . '-' . $request->year))->endOfMonth();

        foreach ($employees as $employee) {


            $dataTillToday = array_fill(1, $now->copy()->format('d'), 'Absent');

            $dataFromTomorrow = [];
            if (($now->copy()->addDay()->format('d') != $this->daysInMonth) && !$requestedDate->isPast()) {
                $dataFromTomorrow = array_fill($now->copy()->addDay()->format('d'), ($this->daysInMonth - $now->copy()->format('d')), '-');
            } else {
                $dataFromTomorrow = array_fill($now->copy()->addDay()->format('d'), ($this->daysInMonth - $now->copy()->format('d')), 'Absent');
            }
            $final[$employee->id . '#' . $employee->name] = array_replace($dataTillToday, $dataFromTomorrow);

            foreach ($employee->attendance as $attendance) {
                $final[$employee->id . '#' . $employee->name][Carbon::parse($attendance->clock_in_time)->timezone($this->global->timezone)->day] = '<a href="javascript:;" class="view-attendance" data-attendance-id="' . $attendance->id . '"><i class="fa fa-check text-success"></i></a>';
            }

            $image = '<img src="' . $employee->image_url . '" alt="user" class="img-circle" width="30" height="30"> ';
            $final[$employee->id . '#' . $employee->name][] = '<a class="userData" id="userID' . $employee->id . '" data-employee-id="' . $employee->id . '"  href="' . route('admin.employees.show', $employee->id) . '">' . $image . ' ' . ucwords($employee->name) . '</a>';

            foreach ($this->holidays as $holiday) {
                $final[$employee->id . '#' . $employee->name][$holiday->date->day] = 'Holiday';
            }
        }


        $this->employeeAttendence = $final;


        $view = view('admin.attendance.summary_data', $this->data)->render();
        return Reply::dataOnly(['status' => 'success', 'data' => $view]);
    }

    public function detail($id)
    {
        $explodeId = explode('-', $id);
        if (isset($explodeId[1]) && !empty($explodeId[1])){
         // ini adalah ijin/leave
            // json date leave
            $id = explode('-',$id);
            $leave = Leave::join('leave_types as lt','lt.id','leaves.leave_type_id')
                ->where('leaves.id',$id[1])
                ->select('leaves.*','lt.display_name','lt.type_name')
                ->first();

            if ($leave->type_name=='Ijin') {
                // get child
                $child = LeaveIjin::where('leave_id', $leave->id)->first();
                $leave->child = $child;
            }
            // dd($leave);
            $this->leave = $leave;
            // return view('admin.attendance.attendance_info_leave', $this->data);
            $attendance = Attendance::find($id[0]);
        }else{
            $attendance = Attendance::find($id);
        }
        $this->attendanceActivity = Attendance::userAttendanceByDate($attendance->clock_in_after_timezone->format('Y-m-d'), $attendance->clock_in_after_timezone->format('Y-m-d'), $attendance->user_id);
        
        $this->firstClockIn = Attendance::where(DB::raw('DATE(attendances.clock_in_after_timezone)'), $attendance->clock_in_after_timezone->format('Y-m-d'))
        // $this->firstClockIn = Attendance::where(DB::raw('DATE(DATE_ADD(attendances.clock_in_time, INTERVAL 7 HOUR))'), $attendance->clock_in_time->format('Y-m-d'))
            ->where('user_id', $attendance->user_id)->orderBy('id', 'asc')->first();
        // dd($attendance->clock_in_time->format('Y-m-d'));
        // \DB::raw('DATE_ADD(attendances.clock_in_time, INTERVAL 7 HOUR)')
        $this->lastClockOut = Attendance::where(DB::raw('DATE(attendances.clock_in_after_timezone)'), $attendance->clock_in_after_timezone->format('Y-m-d'))
        // $this->lastClockOut = Attendance::where(DB::raw('DATE(DATE_ADD(attendances.clock_in_time, INTERVAL 7 HOUR))'), $attendance->clock_in_time->format('Y-m-d'))
            ->where('user_id', $attendance->user_id)->orderBy('id', 'desc')->first();
        // $this->startTime = Carbon::parse($this->firstClockIn->clock_in_after_timezone)->timezone($this->global->timezone);
        $this->startTime = Carbon::parse($this->firstClockIn->clock_in_after_timezone);

        if (!is_null($this->lastClockOut->clock_out_time)) {
            $this->endTime = Carbon::parse($this->lastClockOut->clock_out_after_timezone);
            // dd($this->endTime);
        // } elseif (($this->lastClockOut->clock_in_after_timezone->timezone($this->global->timezone)->format('Y-m-d') != Carbon::now()->timezone($this->global->timezone)->format('Y-m-d')) && is_null($this->lastClockOut->clock_out_time)) {
        } elseif (($this->lastClockOut->clock_in_after_timezone->format('Y-m-d') != Carbon::now()->timezone($this->global->timezone)->format('Y-m-d')) && is_null($this->lastClockOut->clock_out_time)) {
            $this->endTime = Carbon::parse($this->startTime->format('Y-m-d') . ' ' . $this->attendanceSettings->office_end_time, $this->global->timezone);
            $this->notClockedOut = true;
        } else {
            $this->notClockedOut = true;
            // $this->endTime = Carbon::now()->timezone($this->global->timezone);
            $this->endTime = Carbon::now();
            $timezone = $this->lastClockOut->clock_in_timezone;
            if ($timezone>0) {
                $this->endTime->addHours($timezone);
            }else{
                $this->endTime->subHours(abs($timezone));
            }
        }
        $this->attendance = $attendance;
        $this->totalTime = $this->endTime->diff($this->startTime, true)->format('%h.%i');
        // return $this->data;
        $this->employee = EmployeeDetails::leftJoin('cluster_working_hours as cwh', 'cwh.id', 'employee_details.cluster_working_hour_id')
            ->where('employee_details.user_id', $attendance->user_id)
            ->selectRaw('employee_details.*,employee_details.user_id as id,cwh.type as cluster_type,cwh.json as cluster_json,cwh.start_hour as cluster_start_hour,cwh.end_hour as cluster_end_hour')
            ->first();
        
        // $attendance_clock_in_time = date('H:i:s', strtotime($attendance->clock_in_time . ' +7 hours'));
        $attendance_clock_in_time = date('H:i:s', strtotime($attendance->clock_in_after_timezone));

        $this->pertanyaan = AttendancePertanyaan::join('pertanyaans as p', 'p.id', 'attendance_pertanyaans.pertanyaan_id')
            ->where('attendance_pertanyaans.attendance_id', $attendance->id)
            ->selectRaw('p.pertanyaan,attendance_pertanyaans.jawaban')
            ->get();
        // $getUser = User::find($attendance->user_id);
        $jabatan = Designation::find($this->employee->designation_id);
        
        $this->flag_late = false;
        // get cluster from attendance
        $cluster_meta = json_decode($attendance->cluster_meta, true);
        // $start_time_cluster_absen = '-';
        // $end_time_cluster_absen = '-';
        if ($cluster_meta['type'] == 'daily') {
            // check day of today
            // $dayOfToday = getDayInIndonesia(Carbon::now()->format('l'));
            // $dayOfToday = getDayInIndonesia(date('l', strtotime($attendance->clock_in_time . ' +7 hours')));
            $dayOfToday = getDayInIndonesia(date('l', strtotime($attendance->clock_in_after_timezone)));

            // get json
            // $json_cluster = json_decode($this->employee->cluster_json, true);
            $clusterController = new ClusterWorkingHourController;
            $json_cluster = $clusterController->getJsonDaily($cluster_meta['json'], true);

            // seharusnya tetap baca dari cluster meta
            // if(!empty($attendance->cluster_meta)){
            //     $cluster = json_decode($attendance->cluster_meta, true);
            //   	//print_r($cluster); exit();
            //     $json_cluster = json_decode($cluster['json'], true);
            // }
            $office_start_time = date('H:i:s', strtotime($json_cluster[$dayOfToday]['jam_masuk']));
            $office_end_time = date('H:i:s', strtotime($json_cluster[$dayOfToday]['jam_pulang']));
        }else{
            $office_start_time = date('H:i:s', strtotime($cluster_meta['start_hour']));
            $office_end_time = date('H:i:s', strtotime($cluster_meta['end_hour']));
        }
        if ($office_start_time < $attendance_clock_in_time && $jabatan->check_late == 1) {
            $this->flag_late  = true;
        }
        // dd($this->employee->cluster_type);
        // $this->json_cluster = $json_cluster;
        $this->office_start_time = $office_start_time;
        $this->office_end_time = $office_end_time;

        // check attendance
        $getCurrClockInTime = Carbon::parse($attendance->clock_in_after_timezone)->format('Y-m-d');
        $check_attendance = Attendance::checkClockInAndClockOut($this->employee,$getCurrClockInTime);
        
        $this->pulang_awal_system = false;
        if (!$check_attendance) {
            $this->pulang_awal_system = true;
        }
        return view('admin.attendance.attendance_info', $this->data);
    }

    public function mark(Request $request, $userid, $date)
    {
        $this->date = Carbon::parse($date)->format('Y-m-d');
        $this->row = Attendance::attendanceByUserDate($userid, $this->date);
        $this->clock_in = 0;
        $this->total_clock_in = Attendance::where('user_id', $userid)
            ->where(DB::raw('DATE(attendances.clock_in_after_timezone)'), '=', $this->date)
            ->whereNull('attendances.clock_out_time')
            ->count();

        $this->timezone = 7;
        if (!empty($this->row->clock_in_timezone)) {
            $this->timezone = $this->row->clock_in_timezone;
        }
        $this->userid = $userid;
        $this->type = 'add';
        return view('admin.attendance.attendance_mark', $this->data);
    }

    public function storeMark(StoreAttendance $request)
    {
        DB::beginTransaction();
        try {
            $timezone = $request->timezone;
            $employee = EmployeeDetails::where("user_id", $request->user_id)->first();
            $date = Carbon::parse($request->attendance_date)->format('Y-m-d');
            $clockIn = null;
            $clockOut = null;
            $clockInAfterTimezone = null;
            $clockOutAfterTimezone = null;
            $attendance = null;
            
            if (isset($request->clock_in_time) && !empty($request->clock_in_time)){
                $clockInAfterTimezoneOri = Carbon::createFromFormat('Y-m-d g:i A', $date.' '.$request->clock_in_time);
                // request always with timezone
                if ($timezone>=0) {
                    $clockIn = $clockInAfterTimezoneOri->copy()->subHours(abs($timezone))->format('Y-m-d H:i:s');
                }else{
                    $clockIn = $clockInAfterTimezoneOri->copy()->addHours($timezone)->format('Y-m-d H:i:s');
                }
                $clockInAfterTimezone = $clockInAfterTimezoneOri->copy()->format('Y-m-d H:i:s');

                //search attendance by date 
                $attendance = Attendance::where('user_id', $request->user_id)
                    ->where(DB::Raw('DATE(clock_in_after_timezone)'),'=',$clockInAfterTimezoneOri->copy()->format('Y-m-d'))
                    ->first();
            }

            if (isset($request->clock_out_time) && !empty($request->clock_out_time)){ 
                $clockOutAfterTimezoneOri = Carbon::createFromFormat('Y-m-d g:i A', $date.' '.$request->clock_out_time);

                if ($timezone>=0) {
                    $clockOut = $clockOutAfterTimezoneOri->copy()->subHours(abs($timezone))->format('Y-m-d H:i:s');
                }else{
                    $clockOut = $clockOutAfterTimezoneOri->copy()->addHours($timezone)->format('Y-m-d H:i:s');
                }

                $clockOutAfterTimezone = $clockOutAfterTimezoneOri->copy()->format('Y-m-d H:i:s');

                //search attendance by date 
                $attendance = Attendance::where('user_id', $request->user_id)
                    ->where(DB::Raw('DATE(clock_out_after_timezone)'),'=',$clockOutAfterTimezoneOri->copy()->format('Y-m-d'))
                    ->first();
            }
            // if ($request->clock_out_time != '') {
            //     $clockOutAfterTimezone = Carbon::createFromFormat('Y-m-d g:i A', $date.' '.$request->clock_out_time);

            //     if ($timezone>=0) {
            //         $clockOut = $clockOutAfterTimezone->copy()->subHours(abs($timezone))->format('Y-m-d H:i:s');
            //     }else{
            //         $clockOut = $clockOutAfterTimezone->copy()->addHours($timezone)->format('Y-m-d H:i:s');
            //     }

            //     $clockOutAfterTimezone = $clockOutAfterTimezone->copy()->format('Y-m-d H:i:s');

            // } else {
            //     $clockOut = null;
            // }
            $cluster_meta =null;
            $cluster = DB::table('cluster_working_hours')->where('id', $employee->cluster_working_hour_id)->first();
            if ($cluster) {
                $cluster_meta=json_encode($cluster);
            }
            
            if ($attendance) {
                $attendance->clock_in_time = $clockIn;
                $attendance->clock_in_ip = $request->clock_in_ip;
                $attendance->clock_out_time = $clockOut;
                $attendance->clock_out_ip = $request->clock_out_ip;
                $attendance->working_from = $request->working_from;
                $attendance->clock_in_timezone = $timezone;
                $attendance->clock_out_timezone = $timezone;
                $attendance->clock_in_after_timezone = $clockInAfterTimezone;
                $attendance->clock_out_after_timezone = $clockOutAfterTimezone;
                $attendance->save();
            }else{
                // create new
                Attendance::create([
                    'user_id' => $request->user_id,
                    'clock_in_time' => $clockIn,
                    'clock_in_ip' => $request->clock_in_ip,
                    'clock_out_time' => $clockOut,
                    'clock_out_ip' => $request->clock_out_ip,
                    'working_from' => $request->working_from,
                    'late' => 'no',
                    'half_day' => 'no',
                    'cluster_meta' => $cluster_meta,
                    'clock_in_timezone' => $timezone,
                    'clock_out_timezone' => $timezone,
                    'clock_in_after_timezone' => $clockInAfterTimezone,
                    'clock_out_after_timezone' => $clockOutAfterTimezone
                ]);
            }
            DB::commit();
            return Reply::success(__('messages.attendanceSaveSuccess'));
        } catch (\Throwable $th) {
            DB::rollback();
            return Reply::error($th->getMessage());
        }
        
        // $clockIn = Carbon::createFromFormat($this->global->time_format, $request->clock_in_after_timezone, $this->global->timezone);
        // $clockIn->setTimezone('UTC');
        // $clockIn = $clockIn->format('H:i:s');
        // if ($request->clock_out_time != '') {
        //     $clockOut = Carbon::createFromFormat($this->global->time_format, $request->clock_out_time, $this->global->timezone);
        //     $clockOut->setTimezone('UTC');
        //     $clockOut = $clockOut->format('H:i:s');
        //     $clockOut = $date . ' ' . $clockOut;
        // } else {
        //     $clockOut = null;
        // }

        // $attendance = Attendance::where('user_id', $request->user_id)
        //     ->where(DB::raw('DATE(`clock_in_after_timezone`)'), "$date")
        //     ->whereNull('clock_out_time')
        //     ->first();

        // $clockInCount = Attendance::getTotalUserClockIn($date, $request->user_id);

        // if (!is_null($attendance)) {
        //     $attendance->update([
        //         'user_id' => $request->user_id,
        //         'clock_in_time' => $date . ' ' . $clockIn,
        //         'clock_in_ip' => $request->clock_in_ip,
        //         'clock_out_time' => $clockOut,
        //         'clock_out_ip' => $request->clock_out_ip,
        //         'working_from' => $request->working_from,
        //         'late' => ($request->has('late')) ? 'yes' : 'no',
        //         'half_day' => ($request->has('half_day')) ? 'yes' : 'no'
        //     ]);
        // } else {

        //     // Check maximum attendance in a day
        //     if ($clockInCount < $this->attendanceSettings->clockin_in_day) {
        //         Attendance::create([
        //             'user_id' => $request->user_id,
        //             'clock_in_time' => $date . ' ' . $clockIn,
        //             'clock_in_ip' => $request->clock_in_ip,
        //             'clock_out_time' => $clockOut,
        //             'clock_out_ip' => $request->clock_out_ip,
        //             'working_from' => $request->working_from,
        //             'late' => ($request->has('late')) ? 'yes' : 'no',
        //             'half_day' => ($request->has('half_day')) ? 'yes' : 'no'
        //         ]);
        //     } else {
        //         return Reply::error(__('messages.maxColckIn'));
        //     }
        // }

        // return Reply::success(__('messages.attendanceSaveSuccess'));
    }
    public function laporan()
    {
        $this->employees = User::allEmployees();
        $this->teams = Team::all();
        $this->wilayahs = Wilayah::all();
        $now = Carbon::now();
        $this->year = $now->format('Y');
        $this->month = $now->format('m');
        $init_start = Carbon::now()->subMonth()->format('m-Y');
        $init_start = $this->attendanceSettings->start_day_report . '-' . $init_start;
        $init_start = Carbon::parse($init_start);

        $init_end = $init_start->copy()->addMonth();

        $this->init_start = $init_start->copy()->format('d-m-Y');
        $this->init_end = $init_end->copy()->format('d-m-Y');

        $this->subcompanies = SubCompany::where('company_id', $this->user->company_id)->get();
        $this->kapal = Office::where('company_id', $this->user->company_id)->where('is_kapal',1)->get();
        return view('admin.attendance.laporan', $this->data);
    }
    public function laporanData(Request $request)
    {
        $start_date = Carbon::createFromFormat('d-m-Y', $request->start_date);
        $end_date = Carbon::createFromFormat('d-m-Y', $request->end_date);
        $employees = User::with(
            ['attendance' => function ($query) use ($request, $start_date, $end_date) {
                // $query->whereBetween('attendances.clock_in_time', [$start_date, $end_date]);
                $query->where(function($query) use ($start_date,$end_date){
                        // $query->whereDate(\DB::raw('DATE_ADD(attendances.clock_in_time, INTERVAL 7 HOUR)'),'>=',$start_date)
                        //     ->whereDate(\DB::raw('DATE_ADD(attendances.clock_in_time, INTERVAL 7 HOUR)'),'<=',$end_date);
                        $query->whereDate('attendances.clock_in_after_timezone','>=',$start_date)
                            ->whereDate('attendances.clock_in_after_timezone','<=',$end_date);
                });
                // $query->whereBetween(\DB::raw('DATE_ADD(attendances.clock_in_time, INTERVAL 7 HOUR)'), [$start_date, $end_date]);
            }]
        )
        ->join('role_user', 'role_user.user_id', '=', 'users.id')
            ->join('roles', 'roles.id', '=', 'role_user.role_id')
            ->join('employee_details as ed', 'ed.user_id', 'users.id')
            ->join('cluster_working_hours as cwh', 'cwh.id', 'ed.cluster_working_hour_id')
            ->select(
                'users.id', 
                'users.name', 
                'users.email', 
                'users.created_at',
                'users.company_id',
                'cwh.type as cluster_type',
                'cwh.json as cluster_json',
                'cwh.start_hour as cluster_start_hour',
                'cwh.start_hour as cluster_end_hour',
                )
            ->where('roles.name', '<>', 'client')
            ->where('ed.department_id', $request->department)
            ->where('ed.sub_company_id', $request->subcompany)
            ->where('ed.wilayah_id', $request->wilayah);
            if (!empty($request->office_id)) {
                $employees = $employees->where('ed.office_id',$request->office_id);
            }
            $employees = $employees->groupBy('users.id');

        // where by department id
        // if ($request->department != '0') {
        //     $employees = $employees->where('ed.department_id', $request->department);
        // }

        if ($request->userId == '0') {
            $employees = $employees->get();
        } else {
            $employees = $employees->where('users.id', $request->userId)->get();
        }
        $this->employees = $employees;
        // dd($employees);
        $this->holidays = Holiday::whereBetween('holidays.date', [$start_date, $end_date])->get();
        $final = [];

        // $this->daysInMonth = cal_days_in_month(CAL_GREGORIAN, $request->month, $request->year);
        $now = Carbon::now()->timezone($this->global->timezone);
        // $requestedDate = Carbon::parse(Carbon::parse('01-' . $request->month . '-' . $request->year))->endOfMonth();

        // set to public mean not in loop
        $arr_dataTillToday = [];
        // $dataTillToday = array_fill(1, $now->copy()->format('d'), 'Absent');
        // setup dataTillEndDate
        $diff = $start_date->copy()->diffInDays($end_date);

        // set first day
        $arr_dataTillToday[$start_date->copy()->format('d-m-Y')] = 'Absent';
        $setIndex = $start_date->copy();
        for ($i = 0; $i < $diff; $i++) {
            $setIndex = $setIndex->addDay()->format('d-m-Y');

            $arr_dataTillToday[$setIndex] = 'Absent';
            // set back to carbon
            $setIndex  = Carbon::createFromFormat('d-m-Y', $setIndex);
        }
        $this->dataTillEndDate = $arr_dataTillToday;
        foreach ($employees as $employee) {
            // $dataFromTomorrow = [];
            // if (($now->copy()->addDay()->format('d') != $this->daysInMonth) && !$requestedDate->isPast()) {
            //     $dataFromTomorrow = array_fill($now->copy()->addDay()->format('d'), ($this->daysInMonth - $now->copy()->format('d')), '-');
            // } else {
            //     $dataFromTomorrow = array_fill($now->copy()->addDay()->format('d'), ($this->daysInMonth - $now->copy()->format('d')), 'Absent');
            // }
            $final[$employee->id . '#' . $employee->name] = $arr_dataTillToday;
            $arr_date_masuk =[];
            foreach ($employee->attendance as $attendance) {
                array_push($arr_date_masuk, Carbon::parse($attendance->clock_in_after_timezone)->format('d-m-Y'));
                // $final[$employee->id . '#' . $employee->name][Carbon::parse($attendance->clock_in_time)->timezone($this->global->timezone)->format('d-m-Y')] = '<a href="javascript:;" class="view-attendance" data-attendance-id="' . $attendance->id . '"><i class="fa fa-check text-success"></i></a>';
                $final[$employee->id . '#' . $employee->name][Carbon::parse($attendance->clock_in_after_timezone)->format('d-m-Y')] = '<a href="javascript:;" class="view-attendance" data-attendance-id="' . $attendance->id . '"><i class="fa fa-check text-success"></i></a>';
                // $final[$employee->id . '#' . $employee->name][Carbon::parse($attendance->clock_in_time)->timezone($this->global->timezone)->day] = '<a href="javascript:;" class="view-attendance" data-attendance-id="' . $attendance->id . '"><i class="fa fa-check text-success"></i></a>';
            }

            $image = '<img src="' . $employee->image_url . '" alt="user" class="img-circle" width="30" height="30"> ';
            $final[$employee->id . '#' . $employee->name][] = '<a class="userData" id="userID' . $employee->id . '" data-employee-id="' . $employee->id . '"  href="' . route('admin.employees.show', $employee->id) . '">' . $image . ' ' . ucwords($employee->name) . '</a>';
            
            // check ijin, sakit, cuti, alpha
            // check ijin
            foreach ($arr_dataTillToday as $key => $val) {
                
                $checkDate = Carbon::parse($key)->format('Y-m-d');
                $checkIjin = DB::table('leaves')->join('leave_types as lt','lt.id','leaves.leave_type_id')
                    ->where('leaves.company_id',$employee->employeeDetail->company_id)
                    ->where(function($query) use ($checkDate){
                        $query->whereDate('leaves.leave_date','<=',$checkDate)
                            ->whereDate('leaves.leave_date_end','>=',$checkDate);
                    })
                    ->where('leaves.user_id', $employee->id)
                    ->where('lt.type_name','Ijin')
                    ->select('leaves.id')
                    ->first();
                // logic to absence
                $check_attendance = Attendance::checkClockInAndClockOut($employee,$key);
                
                if (!$check_attendance) {
                    $getAttendanceForIjin = Attendance::where('user_id',$employee->id)
                    // ->whereDate(\DB::raw('DATE_ADD(clock_in_time, INTERVAL 7 HOUR)'), $checkDate)
                    ->whereDate('clock_in_after_timezone', $checkDate)
                    ->first();
                    if (empty($getAttendanceForIjin)) {
                        $final[$employee->id . '#' . $employee->name][$key] = 'Absent';
                    }else{
                        $final[$employee->id . '#' . $employee->name][$key] = '<a href="javascript:;" class="view-ijin" data-leave=\''.$getAttendanceForIjin->id.'-'. null .' \'><i class="fa fa-envelope-o text-danger"></i></a>';
                    }
                }
                // if (count($checkIjin)>0) {
                if (!empty($checkIjin)) { 
                    // $final[$employee->id . '#' . $employee->name][$key] = 'Ijin';
                    // check type ijin
                    // get child
                    $child = LeaveIjin::where('leave_id', $checkIjin->id)->first();
                    // dd($child);
                    if ($child->alasan_ijin=='datang-terlambat' || $child->alasan_ijin=='pulang-awal' 
                    || $child->alasan_ijin=='keluar-kantor' ) {
                        // get attendance
                        $getAttendanceForIjin = Attendance::where('user_id',$employee->id)->whereDate('clock_in_after_timezone',$checkDate)->first();
                        if(!empty($getAttendanceForIjin)){
                            // dd($employee->id, $checkDate,$child->alasan_ijin);
                            $final[$employee->id . '#' . $employee->name][$key] = '<a href="javascript:;" class="view-ijin" data-leave=\''.$getAttendanceForIjin->id.'-'. $checkIjin->id .' \'><i class="fa fa-envelope-o text-danger"></i></a>';
                        };
                    }else{
                        $final[$employee->id . '#' . $employee->name][$key] = 'Ijin';
                    }
                    // dd($final);
                }

                //cuti|Cuti Custom
                $checkCuti = DB::table('leaves')->join('leave_types as lt','lt.id','leaves.leave_type_id')
                    ->where('leaves.company_id',$employee->employeeDetail->company_id)
                    ->where(function($query) use ($checkDate){
                        $query->whereDate('leaves.leave_date','<=',$checkDate)
                            ->whereDate('leaves.leave_date_end','>=',$checkDate);
                    })
                    ->where('leaves.user_id', $employee->id)
                    ->whereIn('lt.type_name',['Cuti','Cuti Custom'])
                    ->count();
                if ($checkCuti>0) {
                    $final[$employee->id . '#' . $employee->name][$key] = 'Cuti';
                }

                // pengecekan office open days dari setting attendance
                $setting = AttendanceSetting::where('company_id', company()->id)->first();
                $office_open_days = json_decode($setting->office_open_days);
                $dayW = date('w', strtotime($checkDate));
                
                
                if(!in_array($dayW, $office_open_days)){
                    if ($final[$employee->id . '#' . $employee->name][$key] == 'Absent') {
                        $final[$employee->id . '#' . $employee->name][$key] = 'Holiday';
                    }
                }
            }
            
            // if ($key=='26-06-2022') {
            //     dd($key, $val, $final);
            // }
            // check time clock out
            // dd($arr_date_masuk);
            foreach ($this->holidays as $holiday) {
                if (!in_array($holiday->date->format('d-m-Y'), $arr_date_masuk)) {
                    $final[$employee->id . '#' . $employee->name][$holiday->date->format('d-m-Y')] = 'Holiday';
                }
            }
        }
        $this->employeeAttendence = $final;
        // foreach ($this->employeeAttendence as $key => $attendance) {
        //     return ($attendance);
        // }
        $view = view('admin.attendance.laporan_data', $this->data)->render();
        return Reply::dataOnly(['status' => 'success', 'data' => $view]);
    }
    public function laporanKehadiran()
    {
        $this->employees = User::allEmployees();
        $this->teams = Team::all();
        $this->wilayahs = Wilayah::all();
        $now = Carbon::now();
        $this->year = $now->format('Y');
        $this->month = $now->format('m');
        $init_start = Carbon::now()->subMonth()->format('m-Y');
        $init_start = $this->attendanceSettings->start_day_report . '-' . $init_start;
        $init_start = Carbon::parse($init_start);

        $init_end = $init_start->copy()->addMonth();

        $this->init_start = $init_start->copy()->format('d-m-Y');
        $this->init_end = $init_end->copy()->format('d-m-Y');
        $this->subcompanies = SubCompany::where('company_id', $this->user->company_id)->get();
        $this->kapal = Office::where('company_id', $this->user->company_id)->where('is_kapal',1)->get();
        return view('admin.attendance.laporan_kehadiran', $this->data);
    }
    public function laporanKehadiranData(Request $request)
    {
        $this->employees = Attendance::getLaporanKehadiran($request->start_date,$request->end_date,$request->department,$request->userId,$request->subcompany,$request->wilayah,$request->office_id,$request->libur);
        
        $this->type_cuti = TipeCuti::where('company_id', \Auth::user()->company_id)
        ->get();
        // get all type ijin
        $this->type_ijin = [
            "tidak-masuk",
            "datang-terlambat",
            "pulang-awal",
            "pulang-awal-system",
            "keluar-kantor",
            "sakit",
        ];

        $view = view('admin.attendance.laporan_kehadiran_data', $this->data)->render();
        return Reply::dataOnly(['status' => 'success', 'data' => $view]);
    }
    
    public function laporanKehadiranDetail($id, $start_date, $end_date, $libur)
    {
        // $start_date = Carbon::createFromFormat('d-m-Y', $start_date);
        // $end_date = Carbon::createFromFormat('d-m-Y', $end_date);
        $data = Attendance::getLaporanKehadiranDetail($id,$start_date,$end_date,$libur);

        // get cluster
        $employee = EmployeeDetails::where('user_id', $id)->first(); 
        // $cluster = ClusterWorkingHour::find($employee->cluster_working_hour_id);
        return view('admin.attendance.laporan_kehadiran_detail', (array)$data);
    }

    public function laporanKehadiranLeadtime()
    {
        $this->employees = User::allEmployees();
        $this->teams = Team::all();
        $this->wilayahs = Wilayah::all();
        $now = Carbon::now();
        $this->year = $now->format('Y');
        $this->month = $now->format('m');
        $init_start = Carbon::now()->subMonth()->format('m-Y');
        $init_start = $this->attendanceSettings->start_day_report . '-' . $init_start;
        $init_start = Carbon::parse($init_start);

        $init_end = $init_start->copy()->addMonth();

        $this->init_start = $init_start->copy()->format('d-m-Y');
        $this->init_end = $init_end->copy()->format('d-m-Y');
        $this->subcompanies = SubCompany::where('company_id', $this->user->company_id)->get();
        $this->kapal = Office::where('company_id', $this->user->company_id)->where('is_kapal',1)->get();
        return view('admin.attendance.laporan_kehadiran_leadtime', $this->data);
    }
    public function laporanKehadiranLeadtimeData(Request $request)
    {
        try {
            $getLeaves = Leave::getLaporanLeadtime(
                $request->tanggal_mulai_pembuatan_ijin,
                $request->tanggal_berakhir_pembuatan_ijin,
                $request->pembuatIjin,
                $request->subcompany,
                $request->wilayah,
                $request->department,
                $request->status,
                $request->atasan_1,
                $request->atasan_2,
                $request->hrd,
                $request->office_id
            );
            // dd($getLeaves);
            if ($getLeaves['status']==500) {
                throw new \Exception($getLeaves['message']);
            }
            $this->leaves = $getLeaves['data'];
            // return $this->leaves;
            // $this->type_cuti = TipeCuti::where('company_id', \Auth::user()->company_id)
            // ->get();
            // // get all type ijin
            // $this->type_ijin = [
            //     "tidak-masuk",
            //     "datang-terlambat",
            //     "pulang-awal",
            //     "pulang-awal-system",
            //     "keluar-kantor",
            //     "sakit",
            // ];
    
            $view = view('admin.attendance.laporan_kehadiran_leadtime_data', $this->data)->render();
            return Reply::dataOnly(['status' => 'success', 'data' => $view]);
        } catch (\Throwable $th) {
            return Reply::dataOnly(['status' => 'error', 'data' => $th->getMessage()]);
        }
    }

    public function exportExcel($id, $start_date, $end_date, $department, $subcompany,$wilayah,$office_id, $libur)
    {
        $startDate  = $start_date;
        $endDate    = $end_date;
        $id         = $id;
        $getTypeCuti = TipeCuti::where('company_id', \Auth::user()->company_id)
            ->get();
        $getTypeIjin = [
                "tidak-masuk",
                "datang-terlambat",
                "pulang-awal",
                "keluar-kantor",
                "sakit",
            ];
        // $employees = User::find($id);
        // Generate and return the spreadsheet
        return Excel::download(new AttendanceExport($id, $startDate, $endDate, $department, $getTypeCuti, $subcompany,$wilayah, $getTypeIjin,$office_id, $libur), "attendance-$startDate-$endDate.xlsx");
    }
    public function exportExcelLaporanKehadiranLeadtime($tanggal_mulai_pembuatan_ijin,$tanggal_berakhir_pembuatan_ijin, $pembuatIjin, $subcompany, $wilayah, $department,$status,$office_id)
    {
        // Generate and return the spreadsheet
        return Excel::download(new AttendanceLeadtimeExport($tanggal_mulai_pembuatan_ijin,$tanggal_berakhir_pembuatan_ijin, $pembuatIjin, $subcompany, $wilayah, $department,$status, $office_id), "leave-leadtime-$tanggal_mulai_pembuatan_ijin-sd-$tanggal_berakhir_pembuatan_ijin.xlsx");
    }
  
    public function getDepartmentBySubCompany(Request $request)
    {
      	$employeeDetailDepartmentId = EmployeeDetails::where('sub_company_id', $request->subcompany_id)->groupBy('department_id')->pluck('department_id');
      	$departments = Team::whereIn('id', $employeeDetailDepartmentId)->select('id', 'team_name as text')->get();
      	
        return Reply::dataOnly(['status' => 'success', 'data' => $departments]);
    }
  
    public function getWilayahBySubCompany(Request $request)
    {
        $employeeDetailDepartmentId = EmployeeDetails::where('sub_company_id', $request->subcompany_id)->groupBy('wilayah_id')->pluck('wilayah_id');
        $departments = Wilayah::whereIn('id', $employeeDetailDepartmentId)->select('id', 'name as text')->get();
        
        return Reply::dataOnly(['status' => 'success', 'data' => $departments]);
    }

    public function autoClockOut()
    {
        // get attendance yg belum pulang
        $getAttendance = Attendance::whereNull('clock_out_time')
                    ->get();

        foreach ($getAttendance as $attendance) {
            // 23.50 - 7hour = 16.50
            $clock_out = date("Y-m-d 16:50:00", strtotime($attendance->clock_in_after_timezone));
            $attendance->clock_out_time = $clock_out;
            $attendance->cron_clock_out = 1;
            $attendance->save();
        }

    }
    public function updateClockInOrClockOut(Request $request){
        // dd($request->all());
        DB::beginTransaction();
        try {
            $type = $request->type;
            $attendance_id = $request->attendance_id;
            $time = $request->time;
            $attendance = Attendance::find($attendance_id);
            if ($type=='clock_in') {
                // update clock_in
                // get attendance
                if (isset($attendance) && !empty($attendance)){ 
                    // modif data
                    // actual time
                    $modif_time = Carbon::parse($time)->format('H:i');
                    // minus timezone time
                    if ($attendance->clock_in_timezone>0) {
                        // harus dikurangi
                        $modif_time_minus_timezone = Carbon::parse($time)->subHours(abs($attendance->clock_in_timezone))->format('H:i');
                    }else{
                        $modif_time_minus_timezone = Carbon::parse($time)->addHours(abs($attendance->clock_in_timezone))->format('H:i');
                    }
                    // 
                    $clock_in_time = Carbon::parse($attendance->clock_in_time)->format('Y-m-d');
                    $clock_in_time_modif = Carbon::parse($clock_in_time.' '.$modif_time_minus_timezone);
                    $clock_in_after_timezone = Carbon::parse($attendance->clock_in_after_timezone)->format('Y-m-d');
                    $clock_in_after_timezone_modif = Carbon::parse($clock_in_after_timezone.' '.$modif_time);
                    $attendance->clock_in_time = $clock_in_time_modif;
                    $attendance->clock_in_after_timezone = $clock_in_after_timezone_modif;
                    $attendance->save();
                }
            }else{
                // update clock_out
                // get attendance
                if (isset($attendance) && !empty($attendance)){ 
                    // modif data
                    // actual time
                    $modif_time = Carbon::parse($time)->format('H:i');
                    // minus timezone time
                    if ($attendance->clock_out_timezone>0) {
                        // harus dikurangi
                        $modif_time_minus_timezone = Carbon::parse($time)->subHours(abs($attendance->clock_out_timezone))->format('H:i');
                    }else{
                        $modif_time_minus_timezone = Carbon::parse($time)->addHours(abs($attendance->clock_out_timezone))->format('H:i');
                    }
                    // 
                    $clock_out_time = Carbon::parse($attendance->clock_out_time)->format('Y-m-d');
                    $clock_out_time_modif = Carbon::parse($clock_out_time.' '.$modif_time_minus_timezone);
                    $clock_out_after_timezone = Carbon::parse($attendance->clock_out_after_timezone)->format('Y-m-d');
                    $clock_out_after_timezone_modif = Carbon::parse($clock_out_after_timezone.' '.$modif_time);
                    $attendance->clock_out_time = $clock_out_time_modif;
                    $attendance->clock_out_after_timezone = $clock_out_after_timezone_modif;
                    $attendance->save();
                }
            }
            DB::commit();
            return [
                "status"=> "success",
                "data"=> [
                    "clock_in"=>$modif_time,
                    "clock_out"=>null,
                ]
            ];
        } catch (\Throwable $th) {
            DB::rollback();
            return $th->getMessage();
        }
    }
}
