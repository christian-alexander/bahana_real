<?php

namespace App;

// use App\Observers\SPKObserver;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Modules\RestAPI\Entities\User;

// use App\Scopes\CompanyScope;

class SPKApproval extends Model
{
    protected $table = 'spk_approval';

    public function approved_by_obj()
    {
        return $this->belongsTo(User::class, 'approved_by', 'id');
    }
    public function rejected_by_obj()
    {
        return $this->belongsTo(User::class, 'rejected_by', 'id');
    }
}
