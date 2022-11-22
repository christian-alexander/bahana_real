<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Scopes\CompanyScope;

class FormField extends Model
{
    // model
    protected $table = "form_field";

    public function form()
    {
        return $this->belongsTo('App\Form','form_id','id');
    }
}
