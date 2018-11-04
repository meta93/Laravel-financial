<?php

namespace App\Models\Inventory;

use App\Models\Products\Relationship;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    protected $table= 'purchases';
    protected $guarded = array('id');

    protected $fillable = [
        'compCode',
        'refno',
        'type',
        'pdate',
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
