<?php

namespace App;

use App\Observers\EmployeeDetailObserver;
use App\Scopes\CompanyScope;
use App\Traits\CustomFieldsTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class EmployeeDetails extends BaseModel
{
    use CustomFieldsTrait;

    protected $table = 'employee_details';

    protected $dates = ['joining_date', 'last_date', 'no_notification_start', 'no_notification_end', 'no_notification_updated_at'];

    protected static function boot()
    {
        parent::boot();

        static::observe(EmployeeDetailObserver::class);

        static::addGlobalScope(new CompanyScope);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id')->withoutGlobalScopes(['active']);
    }

    public function designation()
    {
        return $this->belongsTo(Designation::class, 'designation_id');
    }

    public function cabang()
    {
        return $this->belongsTo(Cabang::class, 'cabang_id');
    }
    public function subcompany()
    {
        return $this->belongsTo(SubCompany::class, 'sub_company_id');
    }
    public function wilayah()
    {
        return $this->belongsTo(Wilayah::class, 'wilayah_id');
    }

    public function department()
    {
        return $this->belongsTo(Team::class, 'department_id');
    }
    public static function myAtasanOrangKepercayaan($user_id){
        $data = EmployeeDetails::join('users','users.id','employee_details.user_id')
            ->where("user_orang_kepercayaan", "LIKE", "%\"".$user_id."\"%")
            ->where('employee_details.is_on_orang_kepercayaan', "LIKE",'%"'.$user_id.'":"1"%')
            ->select('users.id','users.email','users.name','employee_details.sub_company_orang_kepercayaan')
            ->get();
        foreach ($data as $val) {
            $sub_company = \json_decode($val->sub_company_orang_kepercayaan, true);
            // dd($sub_company[$user_id]);
            $arr_sub_company=[];
            foreach ($sub_company[$user_id] as $item) {
                array_push($arr_sub_company,SubCompany::find($item));
            }
            $val->sub_company_orang_kepercayaan = $arr_sub_company;
        }
        return $data;
    }
}
