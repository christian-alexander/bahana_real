<?php

namespace App;

use App\Observers\TaskFileObserver;
use App\Scopes\CompanyScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class ProjectTimeLogFile extends BaseModel
{

    protected $appends = ['file_url','icon'];

    public function getFileUrlAttribute()
    {
        return (!is_null($this->external_link)) ? $this->external_link : asset_url_local_s3('project-time-log-files/'.$this->project_time_log_id.'/'.$this->hashname);
    }

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new CompanyScope);
    }
}
