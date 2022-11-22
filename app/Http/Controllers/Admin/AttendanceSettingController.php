<?php

namespace App\Http\Controllers\Admin;

use App\AttendanceSetting;
use App\Helper\Reply;
use App\Http\Requests\AttendanceSetting\UpdateAttendanceSetting;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AttendanceSettingController extends AdminBaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'app.menu.attendanceSettings';
        $this->pageIcon = 'icon-settings';
        $this->middleware(function ($request, $next) {
            if (!in_array('attendance', $this->user->modules)) {
                abort(403);
            }
            return $next($request);
        });
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->ipAddresses = [];
        $this->attendanceSetting = AttendanceSetting::first();
        $this->openDays = json_decode($this->attendanceSetting->office_open_days);
        if (json_decode($this->attendanceSetting->ip_address)) {
            $this->ipAddresses = json_decode($this->attendanceSetting->ip_address, true);
        }
        return view('admin.attendance-settings.index', $this->data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateAttendanceSetting $request, $id)
    {
        // if (isset($request->start_day_report) && !empty($request->start_day_report)) {
        //     if ($request->start_day_report < 1 || $request->start_day_report > 31) {
        //         return Reply::error(__('messages.errorStartDayReport'));
        //     }
        // }
        $setting = AttendanceSetting::where('company_id', company()->id)->first();
        if (isset($request->office_start_time) && !empty($request->office_start_time)) {
            $setting->office_start_time = Carbon::createFromFormat($this->global->time_format, $request->office_start_time);
        }
        if (isset($request->office_end_time) && !empty($request->office_end_time)) {
            $setting->office_end_time = Carbon::createFromFormat($this->global->time_format, $request->office_end_time);
        }
        if (isset($request->halfday_mark_time) && !empty($request->halfday_mark_time)) {
            $setting->halfday_mark_time = Carbon::createFromFormat($this->global->time_format, $request->halfday_mark_time);
        }
        if (isset($request->late_mark_duration) && !empty($request->late_mark_duration)) {
            $setting->late_mark_duration = $request->late_mark_duration;
        }
        if (isset($request->clockin_in_day) && !empty($request->clockin_in_day)) {
            $setting->clockin_in_day = $request->clockin_in_day;
        }
        if (isset($request->toleransi_absen_pulang) && !empty($request->toleransi_absen_pulang)) {
            $setting->toleransi_absen_pulang = $request->toleransi_absen_pulang;
        }
        ($request->employee_clock_in_out == 'yes') ? $setting->employee_clock_in_out = 'yes' : $setting->employee_clock_in_out = 'no';
        $setting->office_open_days = json_encode($request->office_open_days);
        ($request->radius_check == 'yes') ? $setting->radius_check = 'yes' : $setting->radius_check = 'no';
        ($request->ip_check == 'yes') ? $setting->ip_check = 'yes' : $setting->ip_check = 'no';
        $setting->radius = $request->radius;
        $setting->start_day_report = $request->start_day_report;
        $ip_address = [];
        if ($request->ip) {
            foreach ($request->ip as $key => $value)
                if (!empty($value))
                    $ip_address[] = $value;
        }
        $setting->ip_address = $ip_address ? json_encode($ip_address) : NULL;
        $setting->save();

        return Reply::success(__('messages.settingsUpdated'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
