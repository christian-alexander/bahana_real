<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\TicketFile;

class TicketReply extends BaseModel
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $appends = ['files', 'date'];

    public function user(){
        return $this->belongsTo(User::class, 'user_id')->withoutGlobalScopes(['active']);
    }
    public function files(){
        return $this->hasMany(TicketFile::class, 'ticket_reply_id');
    }
    public function getFilesAttribute()
    {	
      	$files = TicketFile::where("ticket_reply_id", $this->id)->get();
        return $files;
    }
    public function getDateAttribute()
    {	
        return $this->created_at;
    }
}
