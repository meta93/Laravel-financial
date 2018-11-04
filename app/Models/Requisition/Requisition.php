<?php

namespace App\Models\Requisition;

use Illuminate\Database\Eloquent\Model;
use App\Models\Products\Product;

class Requisition extends Model
{
    protected $table = 'requisitions';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['compCode', 'refNo', 'reqType', 'reqDate', 'product_id', 'quantity', 'approver', 'description', 'status', 'user_id'];


    public function item()
    {
        return $this->belongsTo(Product::class,'product_id','id');
    }

}
