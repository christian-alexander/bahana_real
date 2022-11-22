<?php

namespace App\DataTables\Admin;

use App\Attendance;
use App\AttendanceSetting;
use App\DataTables\BaseDataTable;
use App\Designation;
use App\Holiday;
use App\Http\Controllers\Admin\ClusterWorkingHourController;
use App\Leave;
use App\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Html\Button;

class AttendanceReportDataTable extends BaseDataTable
{

    /**
     * @param $query
     * @return \Yajra\DataTables\CollectionDataTable|\Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        return datatables()
            ->collection($query)
            ->addIndexColumn();

    }

    /**
     * @param User $model
     * @return \Illuminate\Support\Collection
     */
    public function query(User $model)
    {
        $request = $this->request();

        $start_date = Carbon::createFromFormat('d-m-Y', $request->startDate);
        $end_date = Carbon::createFromFormat('d-m-Y', $request->endDate);

        $employees = $model->join('role_user', 'role_user.user_id', '=', 'users.id')
            ->join('roles', 'roles.id', '=', 'role_user.role_id')
            ->join('employee_details','employee_details.user_id','users.id')
            ->leftJoin('cluster_working_hours as cwh', 'cwh.id', 'employee_details.cluster_working_hour_id')
            ->select(
                'users.id', 
                'users.name', 
                'users.email', 
                'users.created_at', 
                'users.company_id',
                'cwh.type as cluster_type',
                'cwh.json as cluster_json',
                'cwh.start_hour as cluster_start_hour',
                'cwh.end_hour as cluster_end_hour',
                'employee_details.designation_id',
                'users.company_id'
            )
            ->where('roles.name', '<>', 'client')
            ->groupBy('users.id');
        if ($request->employee != 'all') {
            $employees = $employees->where('users.id', $request->employee)->get();
        } else {
            $employees = $employees->get();
        }

        $this->attendanceSettings = AttendanceSetting::first();
        $openDays = json_decode($this->attendanceSettings->office_open_days);
        $this->startDate = $startDate = Carbon::createFromFormat($this->global->date_format, $request->startDate);
        $this->endDate = $endDate = Carbon::createFromFormat($this->global->date_format, $request->endDate);
        $period = CarbonPeriod::create($this->startDate,  $this->endDate);

        $this->totalDays = $totalWorkingDays = $startDate->diffInDaysFiltered(function (Carbon $date) use ($openDays) {
            foreach ($openDays as $day) {
                if ($date->dayOfWeek == $day) {
                    return $date;
                }
            }
        }, $endDate);

        $summaryData = array();
        foreach ($employees as $key => $employee) {
            $summaryData[$key]['user_id'] = $employee->id;
            $summaryData[$key]['name'] = $employee->name;

            $timeLogInMinutes = 0;
            foreach ($period as $date) {
                $attendanceDate = $date->toDateString();
                $this->firstClockIn = Attendance::where(DB::raw('DATE(attendances.clock_in_after_timezone)'), $attendanceDate)
                    ->where('user_id', $employee->id)->orderBy('id', 'asc')->first();

                if (!is_null($this->firstClockIn)) {
                    $this->lastClockOut = Attendance::where(DB::raw('DATE(attendances.clock_in_after_timezone)'), $attendanceDate)
                        ->where('user_id', $employee->id)->orderBy('id', 'desc')->first();

                    // $this->startTime = Carbon::parse($this->firstClockIn->clock_in_time)->timezone($this->global->timezone);
                    $this->startTime = Carbon::parse($this->firstClockIn->clock_in_after_timezone);

                    // if (!is_null($this->lastClockOut->clock_out_time)) {
                    //     $this->endTime = Carbon::parse($this->lastClockOut->clock_out_time)->timezone($this->global->timezone);
                    // } elseif (($this->lastClockOut->clock_in_time->timezone($this->global->timezone)->format('Y-m-d') != Carbon::now()->timezone($this->global->timezone)->format('Y-m-d')) && is_null($this->lastClockOut->clock_out_time)) {
                    //     $this->endTime = Carbon::parse($this->startTime->format('Y-m-d') . ' ' . $this->attendanceSettings->office_end_time, $this->global->timezone);
                    //     $this->notClockedOut = true;
                    // } else {
                    //     $this->notClockedOut = true;
                    //     $this->endTime = Carbon::now()->timezone($this->global->timezone);
                    // }
                    if (!is_null($this->lastClockOut->clock_out_after_timezone)) {
                        $this->endTime = Carbon::parse($this->lastClockOut->clock_out_after_timezone);
                    } elseif (($this->lastClockOut->clock_in_after_timezone->format('Y-m-d') != Carbon::now()->timezone($this->global->timezone)->format('Y-m-d')) && is_null($this->lastClockOut->clock_out_after_timezone)) {
                        $this->endTime = Carbon::parse($this->startTime->format('Y-m-d') . ' ' . $this->attendanceSettings->office_end_time, $this->global->timezone);
                        $this->notClockedOut = true;
                    } else {
                        $this->notClockedOut = true;
                        $this->endTime = Carbon::now()->timezone($this->global->timezone);
                    }

                    $timeLogInMinutes = $timeLogInMinutes + $this->endTime->diffInMinutes($this->startTime, true);
                }
            }
            $timeLog = intdiv($timeLogInMinutes, 60) . ' hrs ';

            if (($timeLogInMinutes % 60) > 0) {
                $timeLog .= ($timeLogInMinutes % 60) . ' mins';
            }
            $libur = 'no';
            // get diff start date and end date in day
            // dd($start_date,$end_date);
            $diff = $start_date->copy()->diffInDays($end_date);
            $terlambat = 0;
            $pulang_tidak_absen = 0;
            $tidak_hadir = 0; // alhpa
            $hadir = 0; 
            $ijin = 0;
            $cuti = 0;
            $dinas_sementara = 0;
            $dinas_luar_kota = 0;
            $jabatan = Designation::find($employee->designation_id);
            $test_arr =[];
            $test_alpha_hadir =[];
            $arr_type_cuti =[];
            $arr_type_ijin =[];
            $arr_test =[];
                $dateLateForEmployee = [];
            $akumulasiLembur = 0;
            for ($i = 0; $i <= $diff; $i++) {
                $start_date_formated = $start_date->copy()->addDays($i)->format('Y-m-d');
                array_push($arr_test,$start_date_formated);
                $end_date_formated = $end_date->copy()->addDays($i)->format('Y-m-d');
                // get attendance by user id and company id
                $getAttendance = Attendance::where('user_id', $employee->id)
                    ->where('company_id', $employee->company_id)
                    // ->where(function($query) use ($start_date_formated,$end_date_formated){
                    //     $query->whereDate('clock_in_time','<=',$start_date_formated)
                    //         ->whereDate('clock_out_time','>=',$start_date_formated);
                    // })
                    // ->whereDate(\DB::raw('DATE_ADD(clock_in_time, INTERVAL 7 HOUR)'), $start_date_formated)
                    ->whereDate('clock_in_after_timezone', $start_date_formated)
                    // ->whereDate('clock_in_time', $start_date_formated)
                    ->get();
                
                // pengecekan office open days dari setting attendance
                $setting = AttendanceSetting::where('company_id', company()->id)->first();
                $office_open_days = json_decode($setting->office_open_days);
                $dayW = date('w', strtotime($start_date_formated));
                
                // check terlambat
                foreach ($getAttendance as $attendance) {
                    // $attendance_clock_in_time = date('H:i:s', strtotime($attendance->clock_in_time . ' +7 hours'));
                    $attendance_clock_in_time = date('H:i:s', strtotime($attendance->clock_in_after_timezone));

                    // no = hari libur tidak dihitung
                    if ($libur=='no') {
                        // jika pilih ya
                        if(in_array($dayW, $office_open_days)){
                            // hari kerja
                        }else{
                            // hari libur
                            continue;
                        }
                    }
                    // get cluster from attendance
                    $cluster_meta = json_decode($attendance->cluster_meta, true);
                    if ($cluster_meta['type'] == 'daily') {
                        // $dayOfToday2 = getDayInIndonesia(date('l', strtotime($attendance->clock_in_time . ' +7 hours')));
                        $dayOfToday2 = getDayInIndonesia(Carbon::parse($attendance->clock_in_after_timezone)->format('l'));
                        // get json
                        // $json_cluster = json_decode($employee->cluster_json, true);
                        $clusterController = new ClusterWorkingHourController;
                        $json_cluster = $clusterController->getJsonDaily($cluster_meta['json'], true);
                        
                            if(isset($json_cluster[$dayOfToday2]['jam_masuk']))
                            $office_start_time = date('H:i:s', strtotime($json_cluster[$dayOfToday2]['jam_masuk']));
                            else
                            $office_start_time = date('H:i:s', strtotime($json_cluster["senin"]['jam_masuk']));
                            
                        
                    }else{
                        $office_start_time = date('H:i:s', strtotime($cluster_meta['start_hour']));
                    }
                    if ($office_start_time < $attendance_clock_in_time && $jabatan->check_late == 1) {
                        $carbonAttendance_clock_in_time = Carbon::parse($attendance_clock_in_time)->format('Y-m-d');
                        // check have ijin terlambat or not
                        $checkIjinTerlambat = Leave::join('leave_types as lt', 'lt.id', 'leaves.leave_type_id')
                            ->join('leave_ijins as li', 'li.leave_id', 'leaves.id')
                            ->where('leaves.company_id', $employee->company_id)
                            ->where('leaves.user_id', $employee->id)
                            ->where('lt.type_name', 'Ijin')
                            ->where('li.alasan_ijin', 'datang-terlambat')
                            ->whereIn('leaves.masking_status', ['in progress', 'done'])
                            ->where(function($query) use ($start_date_formated){
                                // $query->whereDate('leave_date','<=',$carbonAttendance_clock_in_time)
                                // ->whereDate('leave_date_end','>=',$carbonAttendance_clock_in_time);
                                $query->whereDate('leaves.leave_date','<=',Carbon::parse($start_date_formated)->format('Y-m-d'))
                                    ->whereDate('leaves.leave_date_end','>=',Carbon::parse($start_date_formated)->format('Y-m-d'));
                            })
                            ->count();
                            // if ($employee->id=='83') {
                            //     dd($carbonAttendance_clock_in_time,$start_date_formated);
                            // }
                            if ($checkIjinTerlambat == 0) {
                                //$terlambat++; code lama ini saja
                                    $to_time = strtotime($attendance_clock_in_time);
                                    $from_time = strtotime($office_start_time);
                                    $late =  round(abs($to_time - $from_time) / 60,2). " minute"." cluster time:".$office_start_time;
                                // $start_date_formated = date("Y-m-d H:i:s", strtotime($attendance->clock_in_time." +7 hours"));
                                $start_date_formated = Carbon::parse($attendance->clock_in_after_timezone)->format('Y-m-d H:i:s');
                                    $arrLate["value"] = $start_date_formated;
                                    $arrLate["late"] = $late;
                                    
                                    // $check = date("Y-m-d", strtotime($attendance->clock_in_time." +7 hours"));
                                    $check = Carbon::parse($attendance->clock_in_after_timezone)->format('Y-m-d');
                                    if(!in_array($check, $dateLateForEmployee)){
                                    //kalau sudah terlambat sekali tidak usah dihitung lagi (absen bisa lebih dari sekali)
                                    $terlambat++;
                                    // $dateLateForEmployee[] = date("Y-m-d", strtotime($attendance->clock_in_time." +7 hours"));
                                    $dateLateForEmployee[] = Carbon::parse($attendance->clock_in_after_timezone)->format('Y-m-d');
                                }
                            }
                    }

                    // check pulang tidak absen
                    if($attendance->clock_out_time == null){
                        $pulang_tidak_absen++;
                    }
                    else{ //08.02 --- cek lembur ---

                        if ($cluster_meta['type'] == 'daily') {
                            // $dayOfToday2 = getDayInIndonesia(date('l', strtotime($attendance->clock_in_time . ' +7 hours')));
                            $dayOfToday2 = getDayInIndonesia(Carbon::parse($attendance->clock_in_after_timezone)->format('l'));
                            // get json
                            // $json_cluster = json_decode($employee->cluster_json, true);
                            // this function to give default value when schedule is empty
                            $clusterController = new ClusterWorkingHourController;
                            $json_cluster = $clusterController->getJsonDaily($cluster_meta['json'], true);
                            
                            if(isset($json_cluster[$dayOfToday2]['jam_pulang']))
                                $office_end_time = date('H:i:s', strtotime($json_cluster[$dayOfToday2]['jam_pulang']));
                            else
                                $office_end_time = date('H:i:s', strtotime($json_cluster["senin"]['jam_pulang']));

                            // $attendance_clock_out_time = date('Y-m-d H:i:s', strtotime($attendance->clock_out_time . ' +7 hours'));
                            $attendance_clock_out_time = date('Y-m-d H:i:s', strtotime($attendance->clock_out_after_timezone));

                            if ($office_end_time > $attendance_clock_out_time) {

                                    // $clock_in_date = date("Y-m-d", strtotime($attendance->clock_in_time." +7 hours"));
                                    $clock_in_date = Carbon::parse($attendance->clock_in_after_timezone)->format('Y-m-d');
                                    $from = date("Y-m-d H:i:s", strtotime($clock_in_date." ".$office_end_time));
                                    // $from_time = strtotime($attendance_clock_out_time);
                                    $from_time = strtotime($from);
                                    $to_time = strtotime($attendance_clock_out_time);
                                    $lembur =  round(abs($to_time - $from_time) / 60,2). " minute"." cluster time:".$office_end_time;
                                    $akumulasiLembur +=  round(abs($to_time - $from_time) / 60,2);

                            }
                        }
                    }
                }
                $flag_datang_terlambat=0;
                $flag_pulang_awal=0;
                $flag_keluar_kantor=0;
                $getIjin = Leave::join('leave_types as lt', 'lt.id', 'leaves.leave_type_id')
                    ->leftjoin('leave_ijins as li','li.leave_id','leaves.id')
                    ->where('leaves.company_id', $employee->company_id)
                    ->where('leaves.user_id', $employee->id)
                    ->where('lt.type_name', 'Ijin')
                    ->whereIn('leaves.masking_status', ['in progress', 'done'])
                    ->where(function($query) use ($start_date_formated,$end_date_formated){
                        $query->whereDate('leaves.leave_date','<=',Carbon::parse($start_date_formated)->format('Y-m-d'))
                            ->whereDate('leaves.leave_date_end','>=',Carbon::parse($start_date_formated)->format('Y-m-d'));
                        // $query->whereDate('leaves.leave_date','<=',Carbon::parse($start_date_formated->copy())->format('Y-m-d'))
                        //     ->whereDate('leaves.leave_date_end','>=',Carbon::parse($start_date_formated->copy())->format('Y-m-d'));
                    })
                    ->selectRaw('leaves.*,li.alasan_ijin')
                    ->get();
                //check ijin tidak-masuk
                foreach ($getIjin as $val) {
                    if ($val->alasan_ijin=='tidak-masuk') {
                        array_push($arr_type_ijin,[
                            'tidak-masuk' => 1
                        ]);
                    }elseif ($val->alasan_ijin=='sakit') {
                        array_push($arr_type_ijin,[
                            'sakit' => 1
                        ]);
                    }elseif ($val->alasan_ijin=='datang-terlambat') {
                        array_push($arr_type_ijin,[
                            'datang-terlambat' => 1
                        ]);
                        $flag_datang_terlambat=1;
                    }elseif ($val->alasan_ijin=='pulang-awal') {
                        array_push($arr_type_ijin,[
                            'pulang-awal' => 1
                        ]);
                        $flag_pulang_awal=1;
                    }elseif ($val->alasan_ijin=='keluar-kantor') {
                        array_push($arr_type_ijin,[
                            'keluar-kantor' => 1
                        ]);
                        $flag_keluar_kantor=1;
                    }
                }
                $getCuti =0;
                if(in_array($dayW, $office_open_days)){
                    $getCuti = Leave::join('leave_types as lt', 'lt.id', 'leaves.leave_type_id')
                        ->leftjoin('leave_cutis','leave_cutis.leave_id','leaves.id')
                        ->leftjoin('tipe_cutis','tipe_cutis.id','leave_cutis.kategori_cuti')
                        ->where('leaves.company_id', $employee->company_id)
                        ->where('leaves.user_id', $employee->id)
                        ->whereIn('lt.type_name', ['Cuti','Cuti 3 Bulanan','Cuti Custom'])
                        ->whereIn('leaves.masking_status', ['in progress', 'done'])
                        ->where(function($query) use ($start_date_formated,$end_date_formated){
                            $query->whereDate('leaves.leave_date','<=',$start_date_formated)
                                ->whereDate('leaves.leave_date_end','>=',$start_date_formated);
                        })
                        ->selectRaw('leaves.*,tipe_cutis.id as tipe_cutis_id,tipe_cutis.name as tipe_cutis_name')
                        ->count();
                        // ->get();
                    // $cuti += count($getCuti);
                    $cuti += $getCuti;
                    
                }
                if(
                    count($getIjin) == 0 && 
                    $getCuti == 0  
                    ){
                    if (count($getAttendance) == 0) {
                        if ($libur=='no') {
                            // jika pilih ya
                            if(in_array($dayW, $office_open_days)){
                                    // hari kerja
                                    //pengecekan holiday
                                    $holidayDate = date('Y-m-d', strtotime($start_date_formated));
                                    $holidayExist = Holiday::where('date', $holidayDate)->first();
                                    if(empty($holidayExist)){
                                        $tidak_hadir++;
                                        array_push($test_alpha_hadir,$start_date_formated);
                                        array_push($test_arr,$start_date_formated);
                                    }
                                    //pengecekan holiday
                            }
                        }else{
                            // jika pilih tidak/no
                            if(in_array($dayW, $office_open_days)){
                                // hari biasa dan tidak ada attendance/attendance==0
                                $tidak_hadir++;
                                array_push($test_alpha_hadir,$start_date_formated);
                            }else{
                                // hari libur
                                if ($libur=='yes') {
                                    $tidak_hadir++;
                                    array_push($test_alpha_hadir,$start_date_formated);
                                }
                            }
                        }
                    }else{
                        if ($libur!='no') {
                            $hadir++;
                        }else{
                            if(in_array($dayW, $office_open_days)){
                                $hadir++;
                            }
                        }
                    }
                }else{
                    // check jika ijin terlambat/keluar kantor/pulang awal
                    if ($flag_datang_terlambat==1 || $flag_pulang_awal==1 || $flag_keluar_kantor==1) {
                        $hadir++;
                    }
                }
            }
            $arr_output_type_ijin = [];
            $key_ijins=[];
            foreach($arr_type_ijin as $subarr){
                $key_ijins[] = key($subarr);
            }

            // remove duplicate keys
            $key_ijins = array_unique($key_ijins);

            // sum values with same key from $arr_type_cuti and save to $arr_output_type_cuti
            foreach($key_ijins as $key_ijin){
                $arr_output_type_ijin[$key_ijin] = array_sum(array_column($arr_type_ijin,$key_ijin));    
            }
            $employee->hadir = $hadir;
            $employee->alpha = $tidak_hadir;
            
            $employee->type_ijin = $arr_output_type_ijin;
            $employee->terlambat = $terlambat;

            $summaryData[$key]['present_days'] = $employee->hadir;
            $summaryData[$key]['absent_days'] = $employee->alpha;

            $pulang_awal = 0;
            if (isset($arr_output_type_ijin['type_ijin']['pulang-awal']) && !empty($arr_output_type_ijin['type_ijin']['pulang-awal'])){ 
                $pulang_awal = $arr_output_type_ijin['type_ijin']['pulang-awal'];
            }
            
            $summaryData[$key]['half_day_count'] = $pulang_awal;
            $summaryData[$key]['late_day_count'] = $employee->terlambat;
            $summaryData[$key]['hours_clocked'] = $timeLog;
        }
        return collect($summaryData);
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->setTableId('attendance-report-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom("<'row'<'col-md-6'l><'col-md-6'Bf>><'row'<'col-sm-12'tr>><'row'<'col-sm-5'i><'col-sm-7'p>>")
            ->orderBy(0)
            ->destroy(true)
            ->responsive(true)
            ->serverSide(true)
            ->stateSave(true)
            ->processing(true)
            ->language(__("app.datatable"))
            ->buttons(
                Button::make(['extend'=> 'export','buttons' => ['excel', 'csv']])
            )
            ->parameters([
                'initComplete' => 'function () {
                   window.LaravelDataTables["attendance-report-table"].buttons().container()
                    .appendTo( ".bg-title .text-right")
                }',
                'fnDrawCallback' => 'function( oSettings ) {
                    $("body").tooltip({
                        selector: \'[data-toggle="tooltip"]\'
                    })
                }',
                "lengthMenu" => [[5, 10, 15], [5,10,15]]
            ]);
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            ' #' => ['data' => 'DT_RowIndex', 'orderable' =>false, 'searchable' => false ],
            __('app.employee')  => ['data' => 'name', 'name' => 'users.name'],
            __('modules.attendance.present') => ['data' => 'present_days', 'name' => 'present_days'],
            __('modules.attendance.absent') => ['data' => 'absent_days', 'name' => 'absent_days'],
            __('modules.attendance.hoursClocked') => ['data' => 'hours_clocked', 'name' => 'hours_clocked'],
            __('app.days').' '.__('modules.attendance.late') => ['data' => 'late_day_count', 'name' => 'late_day_count'],
            __('modules.attendance.halfDay') => ['data' => 'half_day_count', 'name' => 'half_day_count'],
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'Attendance_report_' . date('YmdHis');
    }

    public function pdf()
    {
        set_time_limit(0);
        if ('snappy' == config('datatables-buttons.pdf_generator', 'snappy')) {
            return $this->snappyPdf();
        }

        $pdf = app('dompdf.wrapper');
        $pdf->loadView('datatables::print', ['data' => $this->getDataForPrint()]);

        return $pdf->download($this->getFilename() . '.pdf');
    }
}
