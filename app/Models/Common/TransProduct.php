<?php

namespace App\Models\Common;

use App\Models\Inventory\Purchase;
use App\Models\Inventory\Sales;
use App\Models\Products\Product;
use Illuminate\Database\Eloquent\Model;

class TransProduct extends Model
{
    protected $table= 'trans_products';
    protected $guarded = array('id');

    protected $fillable = [
        'compCode',
        'refno',
        'contra',
        'reftype',
        'toWhome',
        'product_id',
        'name',
        'quantity',
        'unit_price',
        'total_price',
        'tax_id',
        'tax_total',
        'approved',
        'purchased',
        'received',
        'returned',
        'delevered',
        'remarks',
        'status'

    ];

    public function purchase()
    {
        return $this->belongsTo(Purchase::class);
    }

    public function sales()
    {
        return $this->belongsTo(Sales::class);
    }

    public function item()
    {
        return $this->belongsTo(Product::class,'product_id','id');
    }
}
