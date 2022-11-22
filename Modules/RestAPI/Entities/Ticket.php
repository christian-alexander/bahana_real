<?php

namespace Modules\RestAPI\Entities;


use App\Observers\ProjectObserver;
use App\User;
use Illuminate\Database\Eloquent\Builder;
use App\TicketReply;
use App\TicketType;

class Ticket extends \App\Ticket
{


    protected $default = [
        'id',
        'subject',
        'status',
        'priority',
        'created_at',
      	'user'
    ];

    protected $dates = [
    ];

    protected $hidden = [
    ];

    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];

    protected $filterable = [
        'id',
        'type_id',
        'subject',
        'status',
        'priority',
        'created_at'	
    ];


    protected $appends = [];
    // Override the functions
  
    public function requester(){
        return $this->belongsTo(User::class, 'user_id')->withoutGlobalScopes(['active']);
    }

    public function reply()
    {
        return $this->hasMany(TicketReply::class, 'ticket_id');
    }
  

    public function visibleTo(\App\User $user)
    {
        if ($user->hasRole('admin') || ($user->user_other_role !== 'employee' && $user->can('view_projects'))) {
            return true;
        }
        return in_array($user->id, $this->members->pluck('user_id')->all());
    }

    public function scopeVisibility($query)
    {
        if (api_user()) {

            $user = api_user();
			/*
            if ($user->hasRole('admin') || ($user->user_other_role !== 'employee' && $user->can('view_projects'))) {
                return $query;
            } else if ($user->hasRole('client')) {
                $query->where('projects.client_id', $user->id);
            } else {
                // If employee or client show projects assigned
                $query->whereIn('projects.id', function ($query) use ($user) {
                    $query->select(\DB::raw('DISTINCT(`projects`.`id`)'))
                        ->from('projects')
                        ->join('project_members', 'project_members.project_id', '=', 'projects.id')
                        ->where('project_members.user_id', $user->id);
                });

                return $query;
            }
            */
            return $query;
        }
    }
}
