<?php

namespace App;

use App\Observers\ClusterWorkingHourObserver;
use Illuminate\Database\Eloquent\Model;
use App\Scopes\CompanyScope;
use Carbon\Carbon;

class ClusterWorkingHour extends Model
{
    protected $table = 'cluster_working_hours';
    protected $fillable = ['name', 'start_time', 'end_time'];

    protected static function boot()
    {
        parent::boot();

        static::observe(ClusterWorkingHourObserver::class);

        static::addGlobalScope(new CompanyScope);
    }

    public static function isNowWorkingHour($date,$user_id){
        // date format must be Y-m-d H:i
        // get employee details
        $isNowWorkingHour = true;
        $employeeDetail = EmployeeDetails::join('cluster_working_hours as cwh','cwh.id','employee_details.cluster_working_hour_id')
            ->where('user_id', $user_id)
            ->select(
                'employee_details.*',
                'cwh.type as cluster_type',
                'cwh.start_hour as cluster_start_hour',
                'cwh.end_hour as cluster_end_hour',
                'cwh.json as cluster_json'
            )
            ->first();
        $date_carbon_original = Carbon::parse($date);
        $date_carbon_after_plus_seven = Carbon::parse($date)->addHours(7);
        // get day 
        $day = $date_carbon_original->copy()->format('l');
        $day = getDayInIndonesia($day);
        if ($employeeDetail->cluster_type=='daily') {
            // if daily take from json
            $json = \json_decode($employeeDetail->cluster_json, true);
            if (isset($json[$day]) && !empty($json[$day])){
                // dd($date_carbon_after_plus_seven->copy()->format('Y-m-d').' '.$json[$day]['jam_masuk']);
                $start_date = Carbon::createFromFormat('Y-m-d H:i A',$date_carbon_after_plus_seven->copy()->format('Y-m-d').' '.$json[$day]['jam_masuk']);
                $end_date = Carbon::createFromFormat('Y-m-d H:i A',$date_carbon_after_plus_seven->copy()->format('Y-m-d').' '.$json[$day]['jam_pulang']);
                $check_between = $date_carbon_after_plus_seven->copy()->between($start_date,$end_date);
                if ($check_between) {
                    $isNowWorkingHour = true;
                }else{
                    $isNowWorkingHour = false;
                }
            }else{
                $isNowWorkingHour = false;
            }
        }else{
            $start_date = Carbon::createFromFormat('Y-m-d H:i A',$date_carbon_after_plus_seven->copy()->format('Y-m-d').' '.$employeeDetail->cluster_start_hour);
            $end_date = Carbon::createFromFormat('Y-m-d H:i A',$date_carbon_after_plus_seven->copy()->format('Y-m-d').' '.$employeeDetail->cluster_end_hour);
            $check_between = $date_carbon_after_plus_seven->copy()->between($start_date,$end_date);
            if ($check_between) {
                $isNowWorkingHour = true;
            }else{
                $isNowWorkingHour = false;
            }
        }
        return $isNowWorkingHour;
    }
}
