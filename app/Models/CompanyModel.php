<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyModel extends Model
{
    protected $table='companies';
    protected $guarded = array('id');

    public function transaction()
    {
        return $this->hasMany(TransactionModel::class,'compCode','compCode');
    }

}
