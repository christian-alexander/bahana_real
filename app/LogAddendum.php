<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Scopes\CompanyScope;
use App\Observers\LogAddendumObserver;
use Illuminate\Support\Facades\Auth;

class LogAddendum extends Model
{
    protected static function boot()
    {
        parent::boot();

        static::observe(LogAddendumObserver::class);

        static::addGlobalScope(new CompanyScope);
    }
    public static function logCluster($old, $new)
    {
        // get version
        $getVersion = LogAddendum::where('from_table_id', $new->id)
            ->orderBy('created_at', 'DESC')
            ->first();
        if (!isset($getVersion) && empty($getVersion)) {
            $getVersion = 1;
        } else {
            $getVersion = $getVersion->version + 1;
        }
        $model = new LogAddendum;
        $model->from_table = 'cluster_working_hours';
        $model->from_table_id = $new->id;
        $model->old_value = json_encode($old);
        $model->new_value = json_encode($new);
        $model->version = $getVersion;
        $model->created_by = Auth::user()->id;
        $model->save();
    }
}
