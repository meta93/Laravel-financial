<?php

namespace App\Models\Inventory;

use App\Models\Products\Product;
use Illuminate\Database\Eloquent\Model;

class ItemMovement extends Model
{
    protected $table= 'item_movements';
    protected $guarded = array('id');

    protected $fillable = [
        'compCode',
        'refno',
        'barcode',
        'contra',
        'reftype',
        'product_id',
        'name',
        'quantity',
        'received',
        'returned',
        'delevered',
        'remarks',
        'status',
        'deleted'
    ];

    public function item()
    {
        return $this->belongsTo(Product::class,'product_id','id');
    }

}
