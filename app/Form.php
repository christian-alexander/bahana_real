<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Scopes\CompanyScope;

class Form extends Model
{
    // model
    protected $table = "form";

    public function field()
    {
        return $this->hasMany('App\FormField','form_id','id');
    }
}
