<?php

namespace App\Models\Products;

use App\Models\CompanyModel;
use Illuminate\Database\Eloquent\Model;

class TaxGroup extends Model
{
    protected $table= 'tax_groups';
    protected $guarded = array('id');


    public function taxid()
    {
        return $this->belongsTo(Tax::class,'tax_id','id');
    }

    public function tax1id()
    {
        return $this->belongsTo(Tax::class,'tax1_id','id');
    }

    public function tax2id()
    {
        return $this->belongsTo(Tax::class,'tax2_id','id');
    }

    public function company()
    {
        return $this->belongsTo(CompanyModel::class,'compCode','compCode');
    }

}
