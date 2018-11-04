<?php

namespace App\Models\Inventory;

use App\Models\Products\Relationship;
use Illuminate\Database\Eloquent\Model;

class Delivery extends Model
{
    protected $table= 'deliveries';
    protected $guarded = array('id');

    protected $fillable = [
        'compCode',
        'invoiceno',
        'challanno',
        'challandate',
        'relationship_id',
        'approver',
        'description',
        'status',
        'user_id',
        'deleted'
    ];

    public function relationship()
    {
        return $this->belongsTo(Relationship::class);
    }
}
