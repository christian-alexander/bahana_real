<?php

namespace App;

use App\Observers\NoticeObserver;
use App\Scopes\CompanyScope;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class NoticeRead extends BaseModel
{
    use Notifiable;
    protected $appends = [];

    protected static function boot()
    {
        parent::boot();

        // static::observe(NoticeObserver::class);

        static::addGlobalScope(new CompanyScope);
    }
}
