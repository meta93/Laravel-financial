<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccountModel extends Model
{
    protected $table= 'accounts';
    protected $guarded = array('id');


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function transaction()
    {
        return $this->hasMany(\App\Models\TransactionModel::class);
    }

//    public function trans_cr()
//    {
//        return $this->hasMany(\App\Models\TransactionModel::class, 'accNo');
//    }

    public function getAccNoAttribute($value)
    {
        if($value=='99999999')
        {
            $value=  null;
        }
        return $value;
    }

    public function getAccNameAttribute($value)
    {
        if(is_null($value))
        {
            $value=  'Journal';
        }
        return $value;
    }
}
