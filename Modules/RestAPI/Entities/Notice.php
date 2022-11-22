<?php

namespace Modules\RestAPI\Entities;
use App\EmployeeDetails;
use App\NoticeRead;
use App\SubCompany;
use App\Team;
use Illuminate\Support\Facades\DB;

class Notice extends \App\Notice
{
    // region Properties

    protected $table = 'notices';

    protected $default = [
        'id',
        'heading',
        'description',
        'to',
        'team_id',
        'sub_company_id',
        'created_at',
        'updated_at',
        'files'
    ];

    protected $hidden = [
        
    ];

    protected $filterable = [
        'id',
        'heading',
        'description',
        'to',
        'team_id',
        'sub_company_id',
        'created_at',
        'updated_at',
    ];

    protected $appends = [
        'linkFile',
        'created_by_user',
        'for_sub_company',
        'for_department',
        'already_read'
    ];

    public function visibleTo(\App\User $user)
    {
        if ($user->hasRole('admin')) {
            return true;
        }

        else if ($user->hasRole('client')) {
            return $this->to === 'client';
        }

        if ($user->hasRole('employee')) {
            return $this->to === 'employee';
        }

        return true;

    }

    public function scopeVisibility($query)
    {
        if(api_user()) {

            $user = api_user();
        	$loginEmployee = EmployeeDetails::where('user_id', '=', $user->id)->first();
			
            if($user->hasRole('admin')){
                return $query;
            }
            elseif($user->hasRole('client')){
                $query->where('notices.to', 'client');
            }
            elseif($user->hasRole('employee')){
                $query->where('notices.to', 'employee');
                $query->where('team_id', $loginEmployee->department_id);
            }
			

        }
    }

    public function getAlreadyReadAttribute()
    {
        $userLogin = api_user();
        if (empty($userLogin)) {
            return false;
        }
        $read = NoticeRead::where('user_id',$userLogin->id)->where('notice_id',$this->id)->count();
        if ($read>0) {
            return true;
        }
        return false;
    }
    public function getlinkFileAttribute()
    {
        if (!empty($this->files)) {
            return asset_url_local_s3('attendance/'.$this->files); 
        }
    }
    public function getCreatedByUserAttribute()
    {
        if (!empty($this->created_by)) {
            return DB::table('users')->find($this->created_by);
        }
        return null;
    }
    public function getForSubCompanyAttribute()
    {
        if (!empty($this->sub_company_id)) {
            if (is_numeric($this->sub_company_id)) {
                $data = SubCompany::where('id',$this->sub_company_id)->select('id','name')->get();
            }else{
                $data = SubCompany::whereIn('id',json_decode($this->sub_company_id))->select('id','name')->get();
            }
            // dd($data);
            return $data;
        }
        return 'Semua company';
    }
    public function getForDepartmentAttribute()
    {
        if (!empty($this->team_id)) {
            if (is_numeric($this->team_id)) {
                $data = Team::where('id',$this->team_id)->select('id','team_name')->get();
            }else{
                $data = Team::whereIn('id',json_decode($this->team_id))->select('id','team_name')->get();
            }
            return $data;
        }
        return 'Semua company';
    }
}
