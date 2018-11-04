<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserPrivilegesModel extends Model
{
    protected $table='user_privileges';
    protected $guarded = array('id');


//    public function useCase()
//    {
//        return $this->belongsTo('App\Models\UseCasesModel');
//        return $this->hasMany('App\Models\UseCasesModel','useCaseId');
//    }
}
