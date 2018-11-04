<?php

namespace App\Models\Products;

use Illuminate\Database\Eloquent\Model;

class Tax extends Model
{
    protected $table= 'taxes';
    protected $guarded = array('id');


//    public function getApplicableOnAttribute($value)
//    {
//        switch ($value) {
//            case 'P':
//                $value = 'Purchase';
//                break;
//            case 'S':
//                $value = 'Sales';
//                break;
//        }
//        return $value;
//
//    }
//
//    public function getcalculatingModeAttribute($value)
//    {
//        switch ($value) {
//            case 'P':
//                $value = 'Percentages';
//                break;
//            case 'F':
//                $value = 'FIXED';
//                break;
//        }
//        return $value;
//
//    }

    public function taxgroup()
    {
        return $this->hasMany(TaxGroup::class,'id','tax1id');
    }

}
