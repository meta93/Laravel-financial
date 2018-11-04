<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DepreciationModel extends Model
{
    protected $table='depreciation';
    protected $guarded = array('id');

    public function company()
    {
        return $this->belongsTo(CompanyModel::class,'compCode','compCode');
    }

    public function acc_no()
    {
        return $this->belongsTo(AccountModel::class,'accNo','accNo');
    }

}
