<?php

namespace App\Models\Inventory;

use App\Models\Products\Relationship;
use Illuminate\Database\Eloquent\Model;

class Sales extends Model
{
    protected $table= 'sales';
    protected $guarded = array('id');

    protected $fillable = [
        'compCode',
        'invoiceno',
        'type',
        'invoicedate',
        'relationship_id',
        'invoice_amt',
        'paid_amt',
        'due_amt',
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
