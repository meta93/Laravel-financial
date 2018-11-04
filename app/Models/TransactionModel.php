<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\CompanyModel;

class TransactionModel extends Model
{
    protected $table='transactions';
    protected $guarded = array('id');


//    protected $fillable = ['accDr'];

    public function dr_acc()
    {
        return $this->belongsTo(AccountModel::class,'accDr','accNo');
    }

    public function company()
    {
        return $this->belongsTo(CompanyModel::class,'compCode','compCode');
//        return $this->has_many('authors','book_id');
    }

    public function cr_acc()
    {
//        return AccountModel::where('accNo',$this->crAcc)->first();

        return $this->belongsTo(AccountModel::class,'accCr','accNo');
    }

    public function getjCodeAttribute($value)
    {
//        if($value=='CP')
//        {
//            $value = 'CASH PAYMENT';
//        }
        switch ($value) {
            case 'CP':
                $value = 'CASH PAYMENT';
                break;
            case 'CR':
                $value = 'CASH RECEIPT';
                break;
            case 'BP':
                $value = 'BANK PAYMENT';
                break;
            case 'BR':
                $value = 'BANK RECEIPT';
                break;
            case 'JV':
                $value = 'JOURNAL';
                break;
        }
        return $value;

    }


    /**
     *
     * @param  string  $value
     * @return string
     */
//    public function getAccCrAttribute($value)
//    {
//        if(is_null($value))
//        {
//            $value=  '99999999';
//        }
//        return $value;
//    }
}
