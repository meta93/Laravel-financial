<?php

namespace App\Models\Products;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table= 'products';
    protected $guarded = ['id', 'created_at','taxgrp_id','updated_at','varient','size','color','deleted_at'];

    protected $fillable = [
        'name',
        'price',
        'discount',
        'quantity',
        'description_long',
        'description_short',
        'stuff_included',
        'warranty_period',
        'image',
        'category_id',
        'subcategory_id',
        'brand_id',
        'unit_name',
        'model_id',
        'compCode',
        'productCode',
        'relationship_id',
        'sku',
        'godown_id',
        'tax_id',
        'rack_id',
        'initialPrice',
        'buyPrice',
        'wholesalePrice',
        'retailPrice',
        'unitPrice',
        'reorderpoint',
        'openingQty',
        'openingValue',
        'onhand',
        'committed',
        'incomming',
        'maxonlinestock',
        'minonlineorder',
        'purchaseQty',
        'sellQty',
        'salvageQty',
        'saleable',
        'receivedQty',
        'returnQty',
        'shipping',
        'discount',
        'description_short',
        'description_long',
        'stuff_included',
        'warranty_period',
        'image_large',
        'image',
        'sellable',
        'purchasable',
        'b2bpublish',
        'free',
        'taxable',
        'status',
        'user_id',
        'deleted'

    ];

    // RELATIONSHIPS
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function brand()
    {
        return $this->belongsTo(Brand::class)->whereNotNull('id');
    }
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function unit()
    {
        return $this->belongsTo(Unit::class,'unit_name');
    }


    public static function getItems($filter_data = array())
    {
        if (empty($filter_data)) {
            return Product::all();
        }

        $query = Product::select('id as item_id', 'name', 'unitPrice', 'initialPrice', 'tax_id');

        $query->where('onhand', '>', '0');

        foreach ($filter_data as $key => $value) {
            $query->where($key, 'LIKE', "%" . $value  . "%");
        }

        return $query->get();
    }



}
