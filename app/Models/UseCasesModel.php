<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UseCasesModel extends Model
{
    protected $table='use_cases';
    protected $guarded = array('id');

//    public function privilege()
//    {
//        return $this->hasMany('App\Models\UserPrivilegesModel','id');
//        return $this->belongsTo('App\Models\UserPrivilegesModel');
//    }

}
